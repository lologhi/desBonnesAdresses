<?php

namespace Bonnes\AdressesBundle\EventListener;

use Symfony\Component\Routing\RouterInterface;

use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapListener implements SitemapListenerInterface {

    private $router;
	private $doctrine;

    public function __construct(RouterInterface $router, $doctrine) {
        $this->router = $router;
		$this->doctrine = $doctrine;
    }

    public function populateSitemap(SitemapPopulateEvent $event) {
        $section = $event->getSection();
        if (is_null($section) || $section == 'default') {
            //get absolute homepage url
            $url = $this->router->generate('bonnes_adresses_homepage', array(), true);

            //add homepage url to the urlset named default
            $event->getGenerator()->addUrl(
                new UrlConcrete(
                    $url,
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_HOURLY,
                    1
                ),
                'default'
            );
        }
		$addresses = $this->doctrine->getRepository('BonnesAdressesBundle:Adresse')->findAll();
		foreach($addresses as $address) {
			$url = $this->router->generate('bonnes_adresses_detailpage', array('name' => $address->getSlug()), true);

			//add homepage url to the urlset named default
			$event->getGenerator()->addUrl(
				new UrlConcrete(
					$url,
					new \DateTime(),
					UrlConcrete::CHANGEFREQ_WEEKLY,
					1
				),
				'default'
			);
		}
    }
}
