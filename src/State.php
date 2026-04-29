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
            self::AL->name,
            self::CT->name,
            self::DE->name,
            self::FL->name,
            self::GA->name,
            self::IL->name,
            self::IN->name,
            self::KY->name,
            self::ME->name,
            self::MD->name,
            self::MA->name,
            self::MI->name,
            self::MS->name,
            self::NH->name,
            self::NJ->name,
            self::NY->name,
            self::NC->name,
            self::OH->name,
            self::PA->name,
            self::RI->name,
            self::SC->name,
            self::TN->name,
            self::VT->name,
            self::VA->name,
            self::WV->name,
            self::WI->name,
        ];
    }

    public static function easternStateNames(): array
    {
        return [
            self::AL->value,
            self::CT->value,
            self::DE->value,
            self::FL->value,
            self::GA->value,
            self::IL->value,
            self::IN->value,
            self::KY->value,
            self::ME->value,
            self::MD->value,
            self::MA->value,
            self::MI->value,
            self::MS->value,
            self::NH->value,
            self::NJ->value,
            self::NY->value,
            self::NC->value,
            self::OH->value,
            self::PA->value,
            self::RI->value,
            self::SC->value,
            self::TN->value,
            self::VT->value,
            self::VA->value,
            self::WV->value,
            self::WI->value,
        ];
    }

    public static function easternStates(): array
    {
        return [
            self::AL->name => self::AL->value,
            self::CT->name => self::CT->value,
            self::DE->name => self::DE->value,
            self::FL->name => self::FL->value,
            self::GA->name => self::GA->value,
            self::IL->name => self::IL->value,
            self::IN->name => self::IN->value,
            self::KY->name => self::KY->value,
            self::ME->name => self::ME->value,
            self::MD->name => self::MD->value,
            self::MA->name => self::MA->value,
            self::MI->name => self::MI->value,
            self::MS->name => self::MS->value,
            self::NH->name => self::NH->value,
            self::NJ->name => self::NJ->value,
            self::NY->name => self::NY->value,
            self::NC->name => self::NC->value,
            self::OH->name => self::OH->value,
            self::PA->name => self::PA->value,
            self::RI->name => self::RI->value,
            self::SC->name => self::SC->value,
            self::TN->name => self::TN->value,
            self::VT->name => self::VT->value,
            self::VA->name => self::VA->value,
            self::WV->name => self::WV->value,
            self::WI->name => self::WI->value,
        ];
    }

    public static function westernStateAbbreviations(): array
    {
        return [
            self::AK->name,
            self::AZ->name,
            self::CA->name,
            self::CO->name,
            self::HI->name,
            self::ID->name,
            self::KS->name,
            self::MT->name,
            self::NE->name,
            self::NV->name,
            self::NM->name,
            self::ND->name,
            self::OK->name,
            self::OR->name,
            self::SD->name,
            self::TX->name,
            self::UT->name,
            self::WA->name,
            self::WY->name,
        ];
    }

    public static function westernStateNames(): array
    {
        return [
            self::AK->value,
            self::AZ->value,
            self::CA->value,
            self::CO->value,
            self::HI->value,
            self::ID->value,
            self::KS->value,
            self::MT->value,
            self::NE->value,
            self::NV->value,
            self::NM->value,
            self::ND->value,
            self::OK->value,
            self::OR->value,
            self::SD->value,
            self::TX->value,
            self::UT->value,
            self::WA->value,
            self::WY->value,
        ];
    }

    public static function westernStates(): array
    {
        return [
            self::AK->name => self::AK->value,
            self::AZ->name => self::AZ->value,
            self::CA->name => self::CA->value,
            self::CO->name => self::CO->value,
            self::HI->name => self::HI->value,
            self::ID->name => self::ID->value,
            self::KS->name => self::KS->value,
            self::MT->name => self::MT->value,
            self::NE->name => self::NE->value,
            self::NV->name => self::NV->value,
            self::NM->name => self::NM->value,
            self::ND->name => self::ND->value,
            self::OK->name => self::OK->value,
            self::OR->name => self::OR->value,
            self::SD->name => self::SD->value,
            self::TX->name => self::TX->value,
            self::UT->name => self::UT->value,
            self::WA->name => self::WA->value,
            self::WY->name => self::WY->value,
        ];
    }
}
