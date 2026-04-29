<?php

declare(strict_types = 1);

namespace Amondar\PhpStateFlow;

enum State: string
{
    case AK = 'Alaska';
    case AL = 'Alabama';
    case AZ = 'Arizona';
    case AR = 'Arkansas';
    case CA = 'California';
    case CO = 'Colorado';
    case CT = 'Connecticut';
    case DE = 'Delaware';
    case FL = 'Florida';
    case HI = 'Hawaii';
    case GA = 'Georgia';
    case ID = 'Idaho';
    case IL = 'Illinois';
    case IN = 'Indiana';
    case IA = 'Iowa';
    case KS = 'Kansas';
    case KY = 'Kentucky';
    case LA = 'Louisiana';
    case ME = 'Maine';
    case MD = 'Maryland';
    case MA = 'Massachusetts';
    case MI = 'Michigan';
    case MN = 'Minnesota';
    case MS = 'Mississippi';
    case MO = 'Missouri';
    case MT = 'Montana';
    case NE = 'Nebraska';
    case NV = 'Nevada';
    case NH = 'New Hampshire';
    case NJ = 'New Jersey';
    case NM = 'New Mexico';
    case NY = 'New York';
    case NC = 'North Carolina';
    case ND = 'North Dakota';
    case OH = 'Ohio';
    case OK = 'Oklahoma';
    case OR = 'Oregon';
    case PA = 'Pennsylvania';
    case RI = 'Rhode Island';
    case SC = 'South Carolina';
    case SD = 'South Dakota';
    case TN = 'Tennessee';
    case TX = 'Texas';
    case UT = 'Utah';
    case VT = 'Vermont';
    case VA = 'Virginia';
    case WA = 'Washington';
    case WV = 'West Virginia';
    case WI = 'Wisconsin';
    case WY = 'Wyoming';

    public static function easternStateAbbreviations(): array
    {
        return [
            self::ME->name,
            self::NH->name,
            self::VT->name,
            self::MA->name,
            self::RI->name,
            self::CT->name,
            self::NY->name,
            self::PA->name,
            self::NJ->name,
            self::DE->name,
            self::MD->name,
            self::VA->name,
            self::WV->name,
            self::NC->name,
            self::SC->name,
            self::GA->name,
            self::FL->name,
            self::OH->name,
            self::MI->name,
            self::IN->name,
            self::IL->name,
            self::WI->name,
            self::KY->name,
            self::TN->name,
            self::AL->name,
            self::MS->name,
        ];
    }

    public static function easternStateNames(): array
    {
        return [
            self::ME->value,
            self::NH->value,
            self::VT->value,
            self::MA->value,
            self::RI->value,
            self::CT->value,
            self::NY->value,
            self::PA->value,
            self::NJ->value,
            self::DE->value,
            self::MD->value,
            self::VA->value,
            self::WV->value,
            self::NC->value,
            self::SC->value,
            self::GA->value,
            self::FL->value,
            self::OH->value,
            self::MI->value,
            self::IN->value,
            self::IL->value,
            self::WI->value,
            self::KY->value,
            self::TN->value,
            self::AL->value,
            self::MS->value,
        ];
    }

    public static function westernStateAbbreviations(): array
    {
        return [
            self::AK->name,
            self::HI->name,
            self::CA->name,
            self::OR->name,
            self::WA->name,
            self::ID->name,
            self::MT->name,
            self::WY->name,
            self::NV->name,
            self::UT->name,
            self::CO->name,
            self::AZ->name,
            self::NM->name,
            self::TX->name,
            self::OK->name,
            self::KS->name,
            self::NE->name,
            self::SD->name,
            self::ND->name,
        ];
    }

    public static function westernStateNames(): array
    {
        return [
            self::AK->value,
            self::HI->value,
            self::CA->value,
            self::OR->value,
            self::WA->value,
            self::ID->value,
            self::MT->value,
            self::WY->value,
            self::NV->value,
            self::UT->value,
            self::CO->value,
            self::AZ->value,
            self::NM->value,
            self::TX->value,
            self::OK->value,
            self::KS->value,
            self::NE->value,
            self::SD->value,
            self::ND->value,
        ];
    }
}
