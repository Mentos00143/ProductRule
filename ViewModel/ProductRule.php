<?php

namespace Perspective\ProductRule\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogRule\Api\CatalogRuleRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductRule implements ArgumentInterface
{
    private $catalogRuleRepository;
    private $productRepository;
    private $searchCriteriaBuilder;

    public function __construct(
        CatalogRuleRepositoryInterface $catalogRuleRepository,
        ProductRepositoryInterface $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {

        $this->catalogRuleRepository = $catalogRuleRepository;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }
    public function getRule(): \Magento\CatalogRule\Api\Data\RuleInterface
    {
        return $this->catalogRuleRepository->get(1);
    }

    public function getProductIds(): array
    {
        return $this->getRule()->getMatchingProductIds();
    }

    /**
     * @return ProductInterface[]
     */
    public function getProducts(): array
    {
        return $this->productRepository->getList(
            $this->searchCriteriaBuilder->addFilter('entity_id', array_keys($this->getProductIds()), 'in')->create()
        )->getItems();
    }

}
