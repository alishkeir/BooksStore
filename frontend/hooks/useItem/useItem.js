import { useMutation } from 'react-query';
import { handleApiRequest, getResponseById } from '@libs/api';
import useRequest from '@hooks/useRequest/useRequest';

let requestTemplates = {
  headers: {
    Accept: 'application/json; charset=utf-8',
    'Content-type': 'application/json; charset=utf-8',
  },
  requests: {
    'use-item-author-update': {
      method: 'POST',
      path: '/profile/authors',
      ref: 'customerAuthors',
      request_id: 'use-item-update',
      body: {
        author_id: null,
      },
    },
    'use-item-cart-add': {
      method: 'POST',
      path: '/carts',
      ref: 'add',
      request_id: 'use-item-update',
      body: {
        guest_token: null,
        product: {
          id: 0,
          quantity: 0,
          is_cart_price: 0,
        },
      },
    },
    'use-item-cart-remove': {
      method: 'POST',
      path: '/carts',
      ref: 'remove',
      request_id: 'use-item-update',
      body: {
        guest_token: null,
        product_id: null,
      },
    },
    'use-item-preorder-update': {
      method: 'POST',
      path: '/profile/preorders',
      ref: 'customerPreOrders',
      request_id: 'use-item-update',
      body: {
        product_id: null,
      },
    },
    'use-item-wishlist-update': {
      method: 'POST',
      path: '/profile/wishlist',
      ref: 'customerWishlist',
      request_id: 'use-item-update',
      body: {
        product_id: null,
      },
    },
  },
};

export default function useItem(user) {
  let queryUseItemUpdate = useMutation('use-item-update', (requestUpdateBuild) => handleApiRequest(requestUpdateBuild));
  let requestUseItemUpdate = useRequest(requestTemplates, queryUseItemUpdate);

  function parseCallback(callback) {
    return (data) => {
      let bookAuthorUpdateResponse = getResponseById(data, 'use-item-update');
      if (bookAuthorUpdateResponse?.success) {
        callback(bookAuthorUpdateResponse);
      } else {
        callback(null);
      }
    };
  }

  function userUpdateRemoteItem() {
    requestUseItemUpdate.resetRequest();

    if (user?.type === 'user') {
      requestUseItemUpdate.modifyHeaders((currentHeader) => {
        currentHeader['Authorization'] = `Bearer ${user.token}`;
      });
    }
  }

  function userUpdateRemoteAuthor(authorId, method, onSuccess) {
    userUpdateRemoteItem();

    requestUseItemUpdate.addRequest('use-item-author-update');

    requestUseItemUpdate.modifyRequest('use-item-author-update', (currentRequest) => {
      currentRequest.method = method;
      currentRequest.body.author_id = authorId;
    });

    // Modifying callback for easier access
    let filteredOnSuccess = parseCallback(onSuccess);

    requestUseItemUpdate.commit({ onSuccess: filteredOnSuccess });
  }

  function userUpdateRemotePreorder(productId, method, onSuccess) {
    userUpdateRemoteItem();

    requestUseItemUpdate.addRequest('use-item-preorder-update');

    requestUseItemUpdate.modifyRequest('use-item-preorder-update', (currentRequest) => {
      currentRequest.method = method;
      currentRequest.body.product_id = productId;
    });

    // Modifying callback for easier access
    let filteredOnSuccess = parseCallback(onSuccess);

    requestUseItemUpdate.commit({ onSuccess: filteredOnSuccess });
  }

  function userUpdateRemoteWishlist(productId, method, onSuccess) {
    userUpdateRemoteItem();

    requestUseItemUpdate.addRequest('use-item-wishlist-update');

    requestUseItemUpdate.modifyRequest('use-item-wishlist-update', (currentRequest) => {
      currentRequest.method = method;
      currentRequest.body.product_id = productId;
    });

    // Modifying callback for easier access
    let filteredOnSuccess = parseCallback(onSuccess);

    requestUseItemUpdate.commit({ onSuccess: filteredOnSuccess });
  }

  function userUpdateRemoteCart(productId, quantity, isCartPrice, method, onSuccess) {
    userUpdateRemoteItem();

    if (method === 'POST') {
      requestUseItemUpdate.addRequest('use-item-cart-add');

      requestUseItemUpdate.modifyRequest('use-item-cart-add', (currentRequest) => {
        if (user?.type === 'guest') {
          if (user?.token) {
            currentRequest.body.guest_token = user.token;
          }
        }

        currentRequest.body.product.id = productId;
        currentRequest.body.product.quantity = quantity;
        currentRequest.body.product.is_cart_price = isCartPrice;
      });
    } else if (method === 'DELETE') {
      requestUseItemUpdate.addRequest('use-item-cart-remove');

      requestUseItemUpdate.modifyRequest('use-item-cart-remove', (currentRequest) => {
        if (user?.type === 'guest') {
          if (user?.token) {
            currentRequest.body.guest_token = user.token;
          }
        }

        currentRequest.body.product_id = productId;
      });
    }

    // Modifying callback for easier access
    let filteredOnSuccess = parseCallback(onSuccess);

    requestUseItemUpdate.commit({ onSuccess: filteredOnSuccess });
  }

  function itemAddRemoteAuthor(authorId, onSuccess) {
    userUpdateRemoteAuthor(authorId, 'POST', onSuccess);
  }

  function itemRemoveRemoteAuthor(authorId, onSuccess) {
    userUpdateRemoteAuthor(authorId, 'DELETE', onSuccess);
  }

  function itemAddRemoteWishlist(productId, onSuccess) {
    userUpdateRemoteWishlist(productId, 'POST', onSuccess);
  }

  function itemRemoveRemoteWishlist(productId, onSuccess) {
    userUpdateRemoteWishlist(productId, 'DELETE', onSuccess);
  }

  function itemAddRemotePreorder(productId, onSuccess) {
    userUpdateRemotePreorder(productId, 'POST', onSuccess);
  }

  function itemRemoveRemotePreorder(productId, onSuccess) {
    userUpdateRemotePreorder(productId, 'DELETE', onSuccess);
  }

  function itemAddRemoteCart(productId, quantity, isCartPrice, onSuccess) {
    userUpdateRemoteCart(productId, quantity, isCartPrice, 'POST', onSuccess);
  }

  function itemRemoveRemoteCart(productId, onSuccess) {
    userUpdateRemoteCart(productId, 1, 0, 'DELETE', onSuccess);
  }

  return {
    itemAddRemotePreorder,
    itemRemoveRemotePreorder,
    itemAddRemoteAuthor,
    itemRemoveRemoteAuthor,
    itemAddRemoteWishlist,
    itemRemoveRemoteWishlist,
    itemAddRemoteCart,
    itemRemoveRemoteCart,
  };
}
