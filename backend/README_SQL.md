
<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>



## Álomgyár SQL parancsok

## Fill published at where/if needed

 ```
 UPDATE product

SET published_at = updated_at

WHERE published_before = 1
AND published_at IS NULL
AND state = 0

UPDATE `product` 
SET published_at = "2022-02-28 09:58:36"
WHERE `status` = '1' AND `published_at` IS NULL AND `state` = '0'
```
Query executed OK, 2,077 rows affected

## Get products that get the 25% pre promotion but doesnt have expiration date (should be empty)

```
SELECT product.id, product.slug, pp.price_list, pp.discount_percent
FROM `product`
LEFT JOIN product_price pp ON pp.product_id = product.id AND pp.store = 0
WHERE `published_before` = '1' AND `published_at` IS NULL AND `status` = '1' AND `state` = '0' AND `type` = '0'
AND pp.discount_percent = 25
LIMIT 50

SELECT product.id, product.published_at, product.updated_at , product.published_before, pp.price_list, pp.price_list_original, pp.price_sale, pp.price_sale_original, product.slug
FROM `product`
LEFT JOIN product_price pp ON pp.product_id = product.id AND pp.store = 0
WHERE  `published_at` IS NULL AND `status` = '1' AND `state` = '0'
AND pp.discount_percent = 25 AND pp.price_sale != pp.price_sale_original
ORDER BY product.updated_at
LIMIT 5000
```

## Update product_movements_items sale_price on corrective invoice null value
```
SELECT `pi`.`product_movements_id`, `pi`.`product_id`, `pi`.`sale_price`, `pm`.`destination_id`, `b`.`product_movements_id`, `b`.`product_id`, `b`.`sale_price`, `b`.`destination_id`
FROM `product_movements_items` as pi
join `product_movements` as pm on `pm`.`id` = `pi`.`product_movements_id`
join (
select `pii`.`product_movements_id`, `pii`.`product_id`, `pii`.`sale_price`, `pmm`.`destination_id`
from `product_movements_items` as pii
join `product_movements` as pmm on `pmm`.`id` = `pii`.`product_movements_id`
where `pii`.`created_at` >= '2022-03-01' and `pii`.`sale_price` is not null
and destination_type in (1,2)
and source_type != 'storno' and is_canceled = 0
) as b on `b`.`destination_id` = `pm`.`destination_id` and `pi`.`product_id` = `b`.`product_id`
WHERE `pi`.`created_at` >= '2022-03-01' AND `pi`.`sale_price` IS NULL
and destination_type in (1,2)
and source_type != 'storno' and is_canceled = 0;
```
```
update `product_movements_items` as pi
join `product_movements` as pm on `pm`.`id` = `pi`.`product_movements_id`
join (
select `pii`.`product_movements_id`, `pii`.`product_id`, `pii`.`sale_price`, `pmm`.`destination_id`
from `product_movements_items` as pii
join `product_movements` as pmm on `pmm`.`id` = `pii`.`product_movements_id`
where `pii`.`created_at` >= '2022-03-01' and `pii`.`sale_price` is not null
and destination_type in (1,2)
and source_type != 'storno' and is_canceled = 0
) as b on `b`.`destination_id` = `pm`.`destination_id` and `pi`.`product_id` = `b`.`product_id`
set pi.sale_price = b.sale_price
WHERE `pi`.`created_at` >= '2022-03-01' AND `pi`.`sale_price` IS NULL
and destination_type in (1,2)
and source_type != 'storno' and is_canceled = 0;
```

## orders.processed_at oszlop feltöltése értékkel
### Először azok, ahol készült számla/nyugta
```
update orders
left join `product_movements` on `product_movements`.`destination_id` = `orders`.`id`
set orders.processed_at = product_movements.created_at
where `destination_type` in (1,2)
and orders.processed_at is null
```
### Végül azok, ahol nem készült bizonylat, mert ebook
```
update orders
set orders.processed_at = orders.created_at
where orders.processed_at is null
```

## Product_movements_items sale_price update az order_items price-ból
```
update `product_movements_items` as pi
join `product_movements` as pm on `pm`.`id` = `pi`.`product_movements_id`
join order_items as oi on oi.order_id = pm.destination_id and oi.product_id = pi.product_id
set `pi`.`sale_price` = oi.price
WHERE `pi`.`created_at` >= '2022-01-01 00:00:00'
and pm.is_canceled = 0
and pm.destination_type in (1,2)
and pi.sale_price is null;
```