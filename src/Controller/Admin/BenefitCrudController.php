<?php

declare(strict_types=1);

namespace BenefitBundle\Controller\Admin;

use BenefitBundle\Entity\Benefit;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

#[AdminCrud(
    routePath: '/benefit/benefit',
    routeName: 'benefit_benefit'
)]
final class BenefitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Benefit::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('福利')
            ->setEntityLabelInPlural('福利管理')
            ->setPageTitle(Crud::PAGE_INDEX, '福利列表')
            ->setPageTitle(Crud::PAGE_NEW, '创建福利')
            ->setPageTitle(Crud::PAGE_EDIT, '编辑福利')
            ->setPageTitle(Crud::PAGE_DETAIL, '福利详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['name', 'type', 'description'])
            ->showEntityActionsInlined()
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')->onlyOnIndex();
        yield TextField::new('name', '福利名称');
        yield TextareaField::new('description', '福利描述')
            ->hideOnIndex()
        ;
        yield MoneyField::new('amount', '福利金额')
            ->setCurrency('CNY')
            ->setNumDecimals(2)
        ;
        yield TextField::new('type', '福利类型');
        yield BooleanField::new('active', '是否激活')
            ->renderAsSwitch(false)
        ;
        yield TextareaField::new('remark', '备注')
            ->hideOnIndex()
        ;
        yield DateTimeField::new('createTime', '创建时间')
            ->hideOnForm()
        ;
        yield DateTimeField::new('updateTime', '更新时间')
            ->onlyOnDetail()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('name')
            ->add('type')
            ->add(BooleanFilter::new('active', '是否激活'))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
        ;
    }
}
