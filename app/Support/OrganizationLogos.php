<?php

namespace App\Support;

/** Verified official assets, prepared locally for the explicit portfolio import. */
final class OrganizationLogos
{
    public static function all(): array
    {
        return [
            'ns' => ['organizations' => ['Nederlandse Spoorwegen', 'NS Leercentrum'], 'label' => 'NS', 'source' => 'https://www.ns.nl/', 'background' => 'light'],
            'tsc' => ['organizations' => ['TSC Crowd Management'], 'label' => 'TSC Crowd Management', 'source' => 'https://www.tsc.nl/', 'background' => 'light'],
            'qarin' => ['organizations' => ['Qarin B.V.'], 'label' => 'Qarin', 'source' => 'https://qarin.nl/pers/', 'background' => 'light'],
            'profi-sec' => ['organizations' => ['PROFI-SEC Security & Crowdmanagement'], 'label' => 'PROFI-SEC', 'source' => 'https://profi-sec.nl/', 'background' => 'dark'],
            'efteling' => ['organizations' => ['Efteling'], 'label' => 'Efteling', 'source' => 'https://www.efteling.com/nl', 'background' => 'light'],
            'svpb' => ['organizations' => ['SVPB'], 'label' => 'SVPB', 'source' => 'https://www.svpb.nl/', 'background' => 'light'],
            'railcenter' => ['organizations' => ['Railcenter'], 'label' => 'Railcenter', 'source' => 'https://www.railcenter.nl/', 'background' => 'dark'],
        ];
    }
}
