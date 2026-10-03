<?php

namespace App\Support;

class MerchantSchema
{
    /**
     * Organization + OnlineStore + ContactPoint sharing the same NAP.
     *
     * @return array<string, mixed>
     */
    public static function graph(): array
    {
        $nap = config('merchant.nap');
        $orgId = url('/').'/#organization';
        $address = [
            '@type' => 'PostalAddress',
            'streetAddress' => $nap['street'],
            'postalCode' => $nap['postal_code'],
            'addressLocality' => $nap['locality'],
            'addressRegion' => $nap['region'],
            'addressCountry' => $nap['country'],
        ];

        $organization = [
            '@type' => 'Organization',
            '@id' => $orgId,
            'name' => $nap['commercial_name'] ?? $nap['legal_name'],
            'legalName' => $nap['legal_name'],
            'vatID' => $nap['vat_id'],
            'taxID' => $nap['tax_id'],
            'url' => url('/'),
            'logo' => asset('wp-content/uploads/2022/01/er-01-scaled.png'),
            'telephone' => $nap['telephone'],
            'address' => $address,
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'telephone' => $nap['telephone'],
                'availableLanguage' => 'es',
            ],
        ];

        if (! empty($nap['alternate_name'])) {
            $organization['alternateName'] = $nap['alternate_name'];
        }

        if (! empty($nap['email'])) {
            $organization['email'] = $nap['email'];
            $organization['contactPoint']['email'] = $nap['email'];
        }

        $store = [
            '@type' => 'OnlineStore',
            '@id' => url('/').'/#store',
            'name' => $nap['commercial_name'] ?? $nap['legal_name'],
            'url' => url('/'),
            'parentOrganization' => ['@id' => $orgId],
            'telephone' => $nap['telephone'],
            'address' => $address,
            'currenciesAccepted' => config('merchant.currency', 'EUR'),
        ];

        if (! empty($nap['email'])) {
            $store['email'] = $nap['email'];
        }

        $localBusiness = [
            '@type' => 'LocalBusiness',
            '@id' => url('/').'/#localbusiness',
            'name' => $nap['commercial_name'] ?? $nap['legal_name'],
            'legalName' => $nap['legal_name'],
            'vatID' => $nap['vat_id'],
            'parentOrganization' => ['@id' => $orgId],
            'url' => url('/'),
            'image' => asset('wp-content/uploads/2022/01/er-01-scaled.png'),
            'telephone' => $nap['telephone'],
            'address' => $address,
            'areaServed' => [
                '@type' => 'Place',
                'name' => ($nap['locality'] ?? '').', '.($nap['region'] ?? '').', España',
            ],
        ];

        if (! empty($nap['alternate_name'])) {
            $localBusiness['alternateName'] = $nap['alternate_name'];
        }

        if (! empty($nap['email'])) {
            $localBusiness['email'] = $nap['email'];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                $organization,
                $store,
                [
                    '@type' => 'WebSite',
                    '@id' => url('/').'/#website',
                    'url' => url('/'),
                    'name' => $nap['commercial_name'] ?? $nap['legal_name'],
                    'inLanguage' => 'es-ES',
                    'publisher' => ['@id' => $orgId],
                ],
                $localBusiness,
            ],
        ];
    }
}
