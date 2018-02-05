<?php
/**
 * Created by PhpStorm.
 * User: BDHK6353
 * Date: 09/09/2016
 * Time: 11:41
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use BankActivityBundle\Entity\GlobalServiceType;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    /**
     * @return TokenStorage
     */
    protected function getSecurityTokenStorage()
    {
        return $this->container->get('security.token_storage');
    }

    protected function getEntityManager()
    {
        return $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @return AuthorizationChecker
     */
    protected function getSecurityAuthorizationChecker()
    {
        return $this->container->get('security.authorization_checker');
    }

    public function mainTopMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root', array(
            'childrenAttributes' => array(
                'class' => 'nav side-menu',
            ),
        ));

        //ADMIN ACTIONS FIRST LEVEL MENU

        $menu->addChild('admin_users',
            array(
                'label' => 'Gestion utilisateurs',
                'uri' => ' '
            )
        )
            ->setAttribute('prev-icon', 'fa fa-cogs')
            ->setAttribute('icon', 'fa fa-chevron-down');

        $menu['admin_users']->addChild('Tous', array('route' => 'user_index', 'routeParameters' => ['role' => "admin"]))->setAttribute('prev-icon', 'fa fa-users');
        $menu['admin_users']->addChild('Nouveau', array('route' => 'user_new', 'routeParameters' => ['role' => "admin"]))->setAttribute('prev-icon', 'fa fa-user-plus');
        $menu['admin_users']->addChild('Postes', array('route' => 'jobstatus_index'))->setAttribute('prev-icon', 'fa fa-briefcase');
//        $menu['Admin actions']->addChild('Issuing/Acquiring data', array('route' => 'bankactivity_homepage'))->setAttribute('prev-icon', 'fa fa-database');
//        $menu['Admin actions']->addChild('API Documentation', array('route' => 'nelmio_api_doc_index', 'linkAttributes' => ['target' =>'_blank']))->setAttribute('prev-icon', 'fa fa-database');
//
//
//        //ADMIN MONITORING FIRST LEVEL MENU
//        $menu->addChild('Admin monitoring',
//            array(
//                'label' => 'Admin monitoring',
//                'uri' => ' '
//            )
//        )
//            ->setAttribute('prev-icon', 'fa fa-bar-chart')
//            ->setAttribute('icon', 'fa fa-chevron-down');
//
//        $menu['Admin monitoring']->addChild('All projects', array('route' => 'backend_fs_projects_list'))->setAttribute('prev-icon', 'fa fa-tasks');
//        $menu['Admin monitoring']->addChild('Session types', array('route' => 'sessiontype_index'))->setAttribute('prev-icon', 'fa fa-book');
////        $menu['Admin monitoring']->addChild('Admin monitoring 2', array('uri' => '#'));
//
//        //ISSUING SESSION FIRST LEVEL MENU
//        $emRepo = $this->getEntityManager()->getRepository("BankActivityBundle:SessionType");
//        if($issuingSessionType = $emRepo->findOneBy(["name" => "Issuing"])){
//            $menu->addChild('Issuing monitoring',
//                array(
//                    'label' => 'Issuing monitoring',
//                    'uri' => ' '
//                )
//            )
//                ->setAttribute('prev-icon', 'fa fa-credit-card')
//                ->setAttribute('icon', 'fa fa-chevron-down');
//
//            $menu['Issuing monitoring']->addChild('Global Services', array('route' => 'globalservicetype_index',
//                'routeParameters' => ['slugSession' => $issuingSessionType->getSlug()]));
//            $menu['Issuing monitoring']->addChild('Platform import (MC1)', array('route' => 'platform-import_index',
//                'routeParameters' => ['slugSession' => $issuingSessionType->getSlug()]));
//            $menu['Issuing monitoring']->addChild('Standard profile import (MC1)', array('route' => 'standard-profile-import_index',
//                'routeParameters' => ['slugSession' => $issuingSessionType->getSlug()]));
//            $menu['Issuing monitoring']->addChild('Applications', array('route' => 'application_index',
//                'routeParameters' => ['slugSession' => $issuingSessionType->getSlug()]));
//
//        }
//        //ACQUIRING SESSION FIRST LEVEL MENU
//        if($acquiringSessionType = $emRepo->findOneBy(["name" => "Acquiring"])){
//
//            $menu->addChild('Acquiring monitoring',
//                array(
//                    'label' => 'Acquiring monitoring',
//                    'uri' => ' '
//                )
//            )
//                ->setAttribute('prev-icon', 'fa fa-calculator')
//                ->setAttribute('icon', 'fa fa-chevron-down');
//
//            $menu['Acquiring monitoring']->addChild('Global Services', array('route' => 'globalservicetype_index',
//                'routeParameters' => ['slugSession' => $acquiringSessionType->getSlug()]));
//            $menu['Acquiring monitoring']->addChild('Certification Service', array('route' => 'certificationservice_index',
//                'routeParameters' => ['slugSession' => $acquiringSessionType->getSlug()]));
//            $menu['Acquiring monitoring']->addChild('Test Support', array('route' => 'test_support_index',
//                'routeParameters' => ['slugSession' => $acquiringSessionType->getSlug()]));
//            $menu['Acquiring monitoring']->addChild('Certification Project Support', array('route' => 'certification_project_support_index',
//                'routeParameters' => ['slugSession' => $acquiringSessionType->getSlug()]));
//            $menu['Acquiring monitoring']->addChild('Terminal Test Configurations', array('route' => 'terminaltestconfiguration_index',
//                'routeParameters' => ['slugSession' => $acquiringSessionType->getSlug()]));
//
//        }
//
//
//        //REQUEST MANAGEMENT
//
//        $menu->addChild('Request management',
//            array(
//                'label' => 'Request management',
//                'uri' => ' '
//            )
//        )
//            ->setAttribute('prev-icon', 'fa fa-shower')
//            ->setAttribute('icon', 'fa fa-chevron-down');
//
//        $menu['Request management']->addChild('New Project', array('route' => 'master_service_product_index'));
//        $menu['Request management']->addChild('Certification Priority', array('route' => 'certification_priority_index'));

        return $menu;
    }
}