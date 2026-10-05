<?php

namespace App\Services;

class EmergencyDirectory
{
    /**
     * @return array<string, mixed>
     */
    public function forCountry(?string $code): array
    {
        $code = strtoupper(trim((string) $code));
        $countries = config('emergency_numbers.countries', []);

        if ($code !== '' && isset($countries[$code])) {
            return [
                'found' => true,
                'country_code' => $code,
                'country_name' => $countries[$code]['name'],
                'numbers' => $countries[$code]['numbers'],
                'note' => $countries[$code]['note'] ?? null,
            ];
        }

        $default = config('emergency_numbers.default');

        return [
            'found' => false,
            'country_code' => $code ?: null,
            'country_name' => null,
            'numbers' => $default['numbers'],
            'note' => $default['note'],
        ];
    }

    /**
     * @return list<array{code: string, name: string}>
     */
    public function countryOptions(): array
    {
        $options = [];
        foreach (config('emergency_numbers.countries', []) as $code => $data) {
            $options[] = ['code' => $code, 'name' => $data['name']];
        }
        usort($options, fn ($a, $b) => strcmp($a['name'], $b['name']));

        return $options;
    }
}
