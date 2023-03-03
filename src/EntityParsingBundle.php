<?php

    namespace EntityParsingBundle;

    use Symfony\Component\HttpKernel\Bundle\Bundle;
    use EntityParsingBundle\DependencyInjection\EntityParsingBundleExtension;
    use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

    class EntityParsingBundle extends Bundle
    {
        public function getContainerExtension(): ?ExtensionInterface
        {
            return new EntityParsingBundleExtension();
        }
    }