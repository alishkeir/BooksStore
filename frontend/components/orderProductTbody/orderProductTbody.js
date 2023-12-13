import React, { useCallback, useState } from 'react';
import { format, parseISO } from 'date-fns/fp';
import ProfileInvoiceDownloadLink from '@components/profileInvoiceDownloadLink/profileInvoiceDownloadLink';
import Icon from '@components/icon/icon';
import colors from '@vars/colors';
import currency from '@libs/currency';
import {
  DeliveryTr,
  GrayIcon,
  GrayTr,
  IconWrapper,
  OrderInfo,
  OrderInfoCol,
  OrderInfoText,
  OrderInfoTitle,
  OrderInfoTr,
  OrderLinkTr,
  OrderProductTbodyComponent,
  ProductAuthors,
  ProductAuthor,
  ProductAuthorTitle,
  ProductCount,
  ProductFinalPrize,
  ProductImage,
  ProductImageTitle,
  ProductMobileTr,
  ProductMobileTrGrid,
  ProductOriginalPrize,
  ProductPrizes,
  ProductSum,
  ProductTitle,
  ProductTr,
  SumTr,
  Td,
} from '@components/orderProductTbody/orderProductTbody.styled';
import OptimizedImage from '@components/Images/OptimizedImage';

export default function OrderProductTbody(order) {
  let [open, setOpen] = useState(false);

  let handleOpenToggle = useCallback(() => {
    setOpen(!open);
  });

  return (
    <OrderProductTbodyComponent>
      <GrayTr onClick={handleOpenToggle}>
        <Td>
          <span>{format('yyyy. MM. dd.', parseISO(order.created_at))}</span>
        </Td>
        <Td>
          <span>{currency.format(order.total_amount)}</span>
        </Td>
        <Td>
          <span>{order.order_id}</span>
        </Td>
        <Td colSpan="2">
          <span style={{ color: order.status.color }}>{order.status.text}</span>
          <GrayIcon>
            <IconWrapper open={open}>
              <Icon type="chevron-right" iconWidth="7px" iconColor={colors.monza}></Icon>
            </IconWrapper>
          </GrayIcon>
        </Td>
      </GrayTr>
      {open && (
        <>
          {order.order_items.map((orderItem, orderItemIndex) => (
            <React.Fragment key={orderItemIndex}>
              <ProductTr className="d-none d-lg-table-row">
                <Td colSpan="2">
                  <ProductImageTitle>
                    <ProductImage>
                      {orderItem?.product_cover && <OptimizedImage src={orderItem.product_cover} layout="intrinsic" width={50} height={50} objectFit="contain" alt=""></OptimizedImage>}
                    </ProductImage>
                    <ProductAuthorTitle>
                      <ProductAuthors>
                        {orderItem.authors.map((author) => (
                          <ProductAuthor key={author.id}>{author.title}</ProductAuthor>
                        ))}
                      </ProductAuthors>
                      <ProductTitle>{orderItem.product_title}</ProductTitle>
                    </ProductAuthorTitle>
                  </ProductImageTitle>
                </Td>
                <Td>
                  <ProductPrizes>
                    <ProductOriginalPrize>{currency.format(orderItem.price)}</ProductOriginalPrize>
                    <ProductFinalPrize>{currency.format(orderItem.price)}</ProductFinalPrize>
                  </ProductPrizes>
                </Td>
                <Td>
                  <ProductCount>{orderItem.quantity} db</ProductCount>
                </Td>
                <Td>
                  <ProductSum>{currency.format(orderItem.total)}</ProductSum>
                </Td>
              </ProductTr>
              <ProductMobileTr className="d-lg-none">
                <Td colSpan="5">
                  <ProductMobileTrGrid>
                    <ProductImage>
                      {orderItem?.product_cover && <OptimizedImage src={orderItem.product_cover} layout="intrinsic" width={50} height={50} objectFit="contain" alt=""></OptimizedImage>}
                    </ProductImage>
                    <ProductAuthorTitle>
                      <ProductAuthors>
                        {orderItem.authors.map((author) => (
                          <ProductAuthor key={author.id}>{author.title}</ProductAuthor>
                        ))}
                      </ProductAuthors>
                      <ProductTitle>{orderItem.product_title}</ProductTitle>
                    </ProductAuthorTitle>
                    <ProductPrizes>
                      <ProductOriginalPrize>{currency.format(orderItem.price)}</ProductOriginalPrize>
                      <ProductFinalPrize>{currency.format(orderItem.price)}</ProductFinalPrize>
                    </ProductPrizes>
                    <ProductCount>{orderItem.quantity} db</ProductCount>
                    <ProductSum>{currency.format(orderItem.total)}</ProductSum>
                  </ProductMobileTrGrid>
                </Td>
              </ProductMobileTr>
            </React.Fragment>
          ))}
          <DeliveryTr>
            <Td colSpan="4">Szállítási díj</Td>
            <Td>{currency.format(order.shipping_fee)}</Td>
          </DeliveryTr>
          <DeliveryTr>
            <Td colSpan="4">Kényelmi költség</Td>
            <Td>{currency.format(order.payment_fee)}</Td>
          </DeliveryTr>
          <SumTr>
            <Td colSpan="4">Összesen</Td>
            <Td>{currency.format(order.total_amount)}</Td>
          </SumTr>
          <OrderInfoTr>
            <Td colSpan="5">
              <OrderInfo>
                <OrderInfoCol>
                  <OrderInfoTitle>Számlázási adatok</OrderInfoTitle>
                  <OrderInfoText>
                    <p>{order.billing_address.full_name}</p>
                    <p>
                      {order.billing_address.city} {order.billing_address.zip_code}
                    </p>
                    <p>
                      {order.billing_address.street} {order.billing_address.street_nr}
                    </p>
                  </OrderInfoText>
                </OrderInfoCol>
                <OrderInfoCol>
                  <OrderInfoTitle>Szállítási adatok</OrderInfoTitle>
                  <OrderInfoText>
                    <p>{order.shipping_address.full_name}</p>
                    <p>
                      {order.shipping_address.city} {order.shipping_address.zip_code}
                    </p>
                    <p>
                      {order.shipping_address.street} {order.shipping_address.street_nr}
                    </p>
                  </OrderInfoText>
                </OrderInfoCol>
                <OrderInfoCol>
                  <OrderInfoTitle>Fizetési mód</OrderInfoTitle>
                  <OrderInfoText>
                    <p>{order.payment_method}</p>
                  </OrderInfoText>
                </OrderInfoCol>
                <OrderInfoCol>
                  <OrderInfoTitle>Szállítási mód</OrderInfoTitle>
                  <OrderInfoText>
                    <p>{order.shipping_method}</p>
                  </OrderInfoText>
                </OrderInfoCol>
              </OrderInfo>
            </Td>
          </OrderInfoTr>
          {order.invoice_url && (
            <OrderLinkTr>
              <Td colSpan="5">
                <ProfileInvoiceDownloadLink id={order.id}>Számla letöltése (PDF)</ProfileInvoiceDownloadLink>
              </Td>
            </OrderLinkTr>
          )}
        </>
      )}
    </OrderProductTbodyComponent>
  );
}
