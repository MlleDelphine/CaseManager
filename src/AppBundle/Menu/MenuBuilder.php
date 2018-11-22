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
        return $this->container->get("security.token_storage");
    }

    protected function getEntityManager()
    {
        return $this->container->get("doctrine.orm.entity_manager");
    }

    /**
     * @return AuthorizationChecker
     */
    protected function getSecurityAuthorizationChecker()
    {
        return $this->container->get("security.authorization_checker");
    }

    public function firstMainTopMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem("root", array(
            "childrenAttributes" => array(
                "class" => "nav side-menu",
            ),
        ));

        //ADMIN ACTIONS FIRST LEVEL MENU

        // USERS MANAGEMENT
        if($this->getSecurityAuthorizationChecker()->isGranted('ROLE_ADMIN')){
            $menu->addChild("admin_users",
                array(
                    "label" => "users_management",
                    "uri" => " "
                )
            )
                ->setAttribute("prev-icon", "fa fa-cogs")
                ->setAttribute("icon", "fa fa-chevron-down");

            $menu["admin_users"]->addChild("employees", array("route" => "user_index"))->setAttribute("prev-icon", "fa fa-users");
            $menu["admin_users"]->addChild("jobs", array("route" => "jobstatus_index"))->setAttribute("prev-icon", "fa fa-briefcase");
            $menu["admin_users"]->addChild("teams", array("route" => "team_index"))->setAttribute("prev-icon", "fa fa-sitemap");
        }

        // CATALOG MANAGEMENT
        $menu->addChild("admin_catalog",
            array(
                "label" => "catalog_management",
                "uri" => " "
            )
        )
            ->setAttribute("prev-icon", "fa fa-book")
            ->setAttribute("icon", "fa fa-chevron-down");

        $menu["admin_catalog"]->addChild("prestation_domains", array("route" => "construction_site_type_index"))->setAttribute("prev-icon", "fa fa-handshake-o");
        $menu["admin_catalog"]->addChild("rates", array("route" => "rate_index"))->setAttribute("prev-icon", "fa fa-percent");

        // RESOURCES MANAGEMENT
        $menu->addChild("admin_resources",
            array(
                "label" => "resources_management",
                "uri" => " "
            )
        )
            ->setAttribute("prev-icon", "fa fa-object-ungroup")
            ->setAttribute("icon", "fa fa-chevron-down");

        $menu["admin_resources"]->addChild("materials", array("route" => "material_index"))->setAttribute("prev-icon", "fa fa-flask");
        $menu["admin_resources"]->addChild("equipments", array("route" => "equipment_index"))->setAttribute("prev-icon", "fa fa-truck");
        $menu["admin_resources"]->addChild("other_resources", array("route" => "resource_index"))->setAttribute("prev-icon", "fa fa-cubes");

        // CUSTOMERS MANAGEMENT
        if($this->getSecurityAuthorizationChecker()->isGranted('ROLE_ADMIN')) {
            $menu->addChild("admin_customers",
                array(
                    "label" => "customers_management",
                    "uri" => " "
                )
            )
                ->setAttribute("prev-icon", "fa fa-building")
                ->setAttribute("icon", "fa fa-chevron-down");

            // ELEMENTS FOR CORPORATION
            $menu["admin_customers"]->addChild("sub_corpo_elements",
                array(
                    "label" => "corporations",
                    "uri" => " "))
                ->setAttribute("prev-icon", "fa fa-building-o")
                ->setAttribute("icon", "fa fa-chevron-down");

            $menu["admin_customers"]["sub_corpo_elements"]->addChild("corpo_groups", array("route" => "corporation_group_index"))->setAttribute("prev-icon", "fa fa-industry");
            $menu["admin_customers"]["sub_corpo_elements"]->addChild("corpo_sites", array("route" => "corporation_site_index"))->setAttribute("prev-icon", "fa fa-building");

            $menu["admin_customers"]->addChild("sub_contacts_elements",
                array(
                    "label" => "contacts",
                    "uri" => " "))
                ->setAttribute("prev-icon", "fa fa-group")
                ->setAttribute("icon", "fa fa-chevron-down");

            $menu["admin_customers"]["sub_contacts_elements"]->addChild("corpo_employees", array("route" => "customer_contact_index"))->setAttribute("prev-icon", "fa fa-address-book");
            $menu["admin_customers"]["sub_contacts_elements"]->addChild("corpo_jobstatuses", array("route" => "corporation_jobstatus_index"))->setAttribute("prev-icon", "fa fa-briefcase");

            $menu["admin_customers"]->addChild("private_individuals", array("route" => "private_individual_index"))->setAttribute("prev-icon", "fa fa-address-card");
            $menu["admin_customers"]->addChild("townships", array("route" => "township_index"))->setAttribute("prev-icon", "fa fa-university");
            $menu["admin_customers"]->addChild("other_customers", array("route" => "other_customer_index"))->setAttribute("prev-icon", "fa fa-share-alt-square");
        }
        return $menu;
    }

    public function secondMainTopMenu(FactoryInterface $factory){
        $menu = $factory->createItem("root", array(
            "childrenAttributes" => array(
                "class" => "nav side-menu",
            ),
        ));
        $menu->addChild("business_management",
            array(
                "label" => "business_management",
                "uri" => " "
            )
        )
            ->setAttribute("prev-icon", "fa fa-folder")
            ->setAttribute("icon", "fa fa-chevron-down");
        $menu['business_management']->addChild('business_cases', array('route' => 'business_case_index'))->setAttribute('prev-icon', 'fa fa-folder-open');
        $menu['business_management']->addChild('business_case_documents', array('route' => 'document_type_index'))->setAttribute('prev-icon', 'fa fa-files');

        return $menu;
    }
}