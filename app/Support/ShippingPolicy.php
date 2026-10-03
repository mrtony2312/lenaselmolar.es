<?php

namespace App\Support;

/**
 * The only place that knows delivery cost, delays and coverage. The product
 * page, cart, checkout, order e-mails, legal pages, JSON-LD and the Google
 * feed all read it, so they cannot contradict each other.
 *
 * A rate is "confirmed" only when merchant.shipping.publish AND
 * merchant.shipping.coverage_confirmed are true. Until then:
 *  - the storefront says the cost and coverage are confirmed before the
 *    order is prepared, and the order total excludes delivery;
 *  - JSON-LD carries no shippingDetails;
 *  - the feed carries no g:shipping;
 *  - merchant:validate reports a CRITICAL account-level issue.
 */
class ShippingPolicy
{
    public static function confirmed(): bool
    {
        return (bool) config('merchant.shipping.publish', false)
            && (bool) config('merchant.shipping.coverage_confirmed', false);
    }

    /**
     * Delays are only published on top of a confirmed rate.
     */
    public static function timesPublished(): bool
    {
        return self::confirmed() && (bool) config('merchant.shipping.publish_transit', false);
    }

    public static function country(): string
    {
        return (string) config('merchant.shipping.country', 'ES');
    }

    /**
     * Amount charged for delivery, or null while not confirmed.
     */
    public static function amount(): ?float
    {
        if (! self::confirmed()) {
            return null;
        }

        return round(max(0.0, Money::toFloat(config('merchant.shipping.price', '0'))), 2);
    }

    public static function isFree(): bool
    {
        return self::amount() === 0.0;
    }

    public static function amountLabel(): string
    {
        $amount = self::amount();

        if ($amount === null) {
            return 'A confirmar';
        }

        return $amount <= 0 ? 'Gratis' : Money::eur($amount);
    }

    public static function handlingMin(): int
    {
        return (int) config('merchant.shipping.handling_min', 1);
    }

    public static function handlingMax(): int
    {
        return (int) config('merchant.shipping.handling_max', 2);
    }

    public static function transitMin(): int
    {
        return (int) config('merchant.shipping.transit_min', 2);
    }

    public static function transitMax(): int
    {
        return (int) config('merchant.shipping.transit_max', 3);
    }

    public static function totalMin(): int
    {
        return self::handlingMin() + self::transitMin();
    }

    public static function totalMax(): int
    {
        return self::handlingMax() + self::transitMax();
    }

    /**
     * One-line label for badges (PDP, contact page, quick view).
     */
    public static function shortLabel(): string
    {
        if (! self::confirmed()) {
            return 'Envío en España: coste y cobertura a confirmar antes de preparar el pedido';
        }

        $cost = self::isFree() ? 'Envío gratuito' : 'Envío: '.self::amountLabel();
        $where = self::country() === 'ES' ? ' (España)' : '';

        if (! self::timesPublished()) {
            return $cost.$where;
        }

        return $cost.': '.self::span(self::totalMin(), self::totalMax()).' laborables'.$where;
    }

    public static function summary(): string
    {
        if (! self::confirmed()) {
            return 'La cobertura y el coste de entrega se confirman antes de preparar el pedido. El importe mostrado es solo el de los productos.';
        }

        $cost = self::isFree()
            ? 'Envío gratuito en toda España.'
            : 'Entrega en toda España. Importe del envío: '.self::amountLabel().' por pedido.';

        if (! self::timesPublished()) {
            return $cost;
        }

        return $cost
            .' Preparación: '.self::span(self::handlingMin(), self::handlingMax()).'.'
            .' Transporte: '.self::span(self::transitMin(), self::transitMax()).'.'
            .' Plazo total: '.self::span(self::totalMin(), self::totalMax()).' laborables.';
    }

    private static function span(int $min, int $max): string
    {
        return $min === $max ? $min.' días' : $min.' a '.$max.' días';
    }
}
