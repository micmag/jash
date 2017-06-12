<h1 class="page-heading product-listing">
    {if isset($title) && $title}
        <span>{$title}</span>
    {else}
        <span>{l s='Default title'}</span>
    {/if}
</h1>

{if isset($desc) && $desc}
    <div class="section-desc">{$desc}</div>
{/if}

{if $products|@count > 0}
<ul class="product_list grid row">
    {foreach from=$products item=product}
        <li class="col-xs-12 col-sm-6 col-md-4">
            <div class="product-container">
                <div class="product-image-container">
                    <a class="product_img_link" href="{$product.link}">
                        <img src="//{$product.image}" alt="{$product.name}" />
                    </a>
                </div>
                <div class="product-name-container">
                    <a href="{$product.link}">{$product.name}</a>
                </div>
                <div class="content_price">
                    <span class="price product-price">{$product.price}</span>
                </div>
            </div>
        </li>
    {/foreach}
</div>
{else}
    <p class="nothing-found">{l s='No products were found for selected criteria.'}</p>
{/if}