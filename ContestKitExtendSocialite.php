<?php

namespace SocialiteProviders\ContestKit;

use SocialiteProviders\Manager\SocialiteWasCalled;

class ContestKitExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param  \SocialiteProviders\Manager\SocialiteWasCalled  $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('contestkit', Provider::class);
    }
}
