<?php

namespace Modules\Admin\Http\Controllers\Tools;
use Twig_Extension;
use Twig_SimpleFunction;

class TwigLandmark extends Twig_Extension
{
    public $router;

    /**
     * Returns a list of filters.
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('baseUrl', array($this, 'generateBaseUrl')),
            new Twig_SimpleFunction('createRoute', array($this, 'createURLRoute')),
            new Twig_SimpleFunction('isRouteSelected', array($this, 'isRouteSelected')),
            new Twig_SimpleFunction('isTabSelected', array($this, 'isTabSelected')),
            new Twig_SimpleFunction('isUser', array($this, 'isUser')),
            new Twig_SimpleFunction('getUser', array($this, 'getUser')),
            new Twig_SimpleFunction('getCompanyAddress', array($this, 'getCompanyAddress')),
            new Twig_SimpleFunction('getCompanyLogoLocation', array($this, 'getCompanyLogoLocation')),
            new Twig_SimpleFunction('getPanelImage', array($this, 'getPanelImage')),
        );
    }

    public function getCompanyLogoLocation() {
        return getCompanyLogoLocation();
    }

    public function getPanelImage($image) {
        return getHomePagePanelImageLink($image);
    }

    protected function logError($error) {
        $date = date('Y_m_d');
        logByDate('siteerrors', 'siteerrors', $error);
    }

    public function createURLRoute($params) {
        try {
            echo $this->router->generate($params['name'], (isset($params['params']) ? $params['params'] : array()));
        } catch(Exception $e) {
            $this->logError($e->getMessage());
        }
    }

    public function generateBaseUrl($fragment="/", $https=false) {
        echo buildUrl($fragment, $https);
    }

    public function isRouteSelected($name) {
        $match = $this->router->match();
        return $match['name'] == $name ? true : false;
    }

    public function isTabSelected($name) {
        return strpos($_SERVER['PATH_INFO'], $name)!==false ? true : false;
    }

    public function isUser() {
        return getUserId() ? true : false;
    }

    public function getUser() {
        return getUser();
    }

    public function getCompanyAddress() {
        return getSetting('company_address') . ' ' . getSetting('company_address2') . ' | ' . getSetting('company_city') . ', ' . getSetting('company_state') . ' ' . getSetting('company_zip');
    }

    public function getSettings() {
        return getSettings();
    }

    /**
     * Returns a list of filters.
     *
     * @return array
     */
    public function getGlobals()
    {
        $glboals = array(
            'basePath' => BP,
            'companyAddress' => $this->getCompanyAddress(),
            'resourcesBaseUrl' => '/webdesign',//'//d38p3m7qcr1us4.cloudfront.net',
            'settings' => $this->getSettings(),
            'blogWidget' => getSiteBlogWidget(),
            'panels' => getActiveHomePagePanels(),
        );
        return $glboals;
    }

    /**
     * Name of this extension
     *
     * @return string
     */
    public function getName()
    {
        return 'Landmark';
    }
}