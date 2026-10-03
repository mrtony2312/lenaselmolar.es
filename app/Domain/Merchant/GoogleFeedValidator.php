<?php

namespace App\Domain\Merchant;

use App\Domain\Merchant\Support\Gtin;
use App\Domain\Merchant\Support\PriceFormatter;
use App\DTO\Merchant\GoogleProductData;

class GoogleFeedValidator
{
    /**
     * @return list<string>
     */
    public function issues(GoogleProductData $item): array
    {
        $issues = [];

        if ($item->id === '' || mb_strlen($item->id) > 50) {
            $issues[] = 'id missing or longer than 50 characters';
        }

        if ($item->title === '' || mb_strlen($item->title) > 150) {
            $issues[] = 'title missing or longer than 150 characters';
        }

        if ($item->description === '') {
            $issues[] = 'description missing';
        }

        if ($item->link === '' || ! str_starts_with($item->link, 'https://')) {
            $issues[] = 'link must be HTTPS';
        }

        if ($item->imageLink === '' || ! str_starts_with($item->imageLink, 'https://')) {
            $issues[] = 'image_link must be HTTPS';
        }

        if ($item->offerAmount <= 0) {
            $issues[] = 'price must be greater than 0';
        }

        if (! preg_match('/^\d+\.\d{2} [A-Z]{3}$/', $item->price)) {
            $issues[] = 'price format must be "29.99 EUR"';
        }

        if ($item->salePrice && ! preg_match('/^\d+\.\d{2} [A-Z]{3}$/', $item->salePrice)) {
            $issues[] = 'sale_price format must be "29.99 EUR"';
        }

        if ($item->gtin && ! Gtin::isValid($item->gtin)) {
            $issues[] = 'gtin checksum invalid';
        }

        // identifier_exists=no is incompatible with a GTIN or an MPN; a brand
        // alone is not a unique identifier and may still be submitted.
        if ($item->sendIdentifierExists && ($item->gtin || $item->mpn)) {
            $issues[] = 'identifier_exists=no cannot be sent with gtin/mpn';
        }

        return $issues;
    }

    public function pricesMatch(GoogleProductData $item, float $domPrice): bool
    {
        return abs($item->offerAmount - round($domPrice, 2)) < 0.005;
    }

    public function formattedOfferPrice(GoogleProductData $item): string
    {
        return PriceFormatter::google($item->offerAmount, $item->currency);
    }
}
