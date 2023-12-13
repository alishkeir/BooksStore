import mitt from 'mitt';

class Analytics {
  constructor() {
    this.isDev = process.env.NODE_ENV === 'development' ? true : false;
    this.queue = {
      itemView: [],
      impression: [],
      addToCart: [],
      addToPreorder: [],
      removeFromPreorder: [],
      removeFromCart: [],
      addPurchase: [],
      beginCheckout: [],
      progressCheckout: [],
      optionsCheckout: [],
      addShippingInfo: [],
      addPaymentInfo: [],
    };

    this.events = mitt();

    // Processing ticks
    this.events.on('tick', () => {
      this.sendQueue();
    });

    // Start ticking the wheel
    if (process.browser) {
      setInterval(() => {
        this.events.emit('tick', 1000);
      }, 1000);
    }
  }

  addImpression(itemObj) {
    if (!this.isDev) this.queue.impression.push(itemObj);
    // console.log(this.queue.impression);
  }

  addItemView(itemObj) {
    if (!this.isDev) this.queue.itemView.push(itemObj);
    // console.log(this.queue.itemView);
  }

  addToCart(itemObj) {
    if (!this.isDev) this.queue.addToCart.push(itemObj);
    // console.log(this.queue.addToCart);
  }

  removeFromCart(itemObj) {
    if (!this.isDev) this.queue.removeFromCart.push(itemObj);
    // console.log(this.queue.removeFromCart);
  }

  addToPreorder(itemObj) {
    if (!this.isDev) this.queue.addToPreorder.push(itemObj);
    // console.log(this.queue.addToPreorder);
  }

  removeFromPreorder(itemObj) {
    if (!this.isDev) this.queue.removeFromPreorder.push(itemObj);
    // console.log(this.queue.removeFromPreorder);
  }

  addPurchase(itemObj) {
    if (!this.isDev) this.queue.addPurchase.push(itemObj);
    // console.log(this.queue.addPurchase);
  }

  beginCheckout(itemObj) {
    if (!this.isDev) this.queue.beginCheckout.push(itemObj);
    // console.log(this.queue.beginCheckout);
  }

  progressCheckout(itemObj) {
    if (!this.isDev) this.queue.progressCheckout.push(itemObj);
    // console.log(this.queue.progressCheckout);
  }

  optionsCheckout(itemObj) {
    if (!this.isDev) this.queue.optionsCheckout.push(itemObj);
    // console.log(this.queue.optionsCheckout);
  }


  addShippingInfo(itemObj) {
    if (!this.isDev) this.queue.addShippingInfo.push(itemObj);
  }

  addPaymentInfo(itemObj) {
    if (!this.isDev) this.queue.addPaymentInfo.push(itemObj);
  }

  sendQueue() {
    if (this.isDev) return;

    // Impressions
    if (window.gtag && this.queue.impression.length > 0) {
      window.gtag('event', 'view_item_list', {
        items: this.queue.impression,
      });

      if (this.isDev) console.log(`${this.queue.impression.length} Impression sent`);

      this.queue.impression = [];
    }

    // Item views
    if (window.gtag && this.queue.itemView.length > 0) {
      window.gtag('event', 'view_item', {
        items: this.queue.itemView,
      });

      if (this.isDev) console.log(`${this.queue.itemView.length} Item view sent`);

      this.queue.itemView = [];
    }

    // Add to cart
    if (window.gtag && this.queue.addToCart.length > 0) {
      window.gtag('event', 'add_to_cart', {
        items: this.queue.addToCart,
      });

      if (this.isDev) console.log(`${this.queue.addToCart.length} Cart additions sent`);

      this.queue.addToCart = [];
    }

    // Remove from cart
    if (window.gtag && this.queue.removeFromCart.length > 0) {
      window.gtag('event', 'remove_from_cart', {
        items: this.queue.removeFromCart,
      });

      if (this.isDev) console.log(`${this.queue.removeFromCart.length} Cart removal sent`);

      this.queue.removeFromCart = [];
    }

    // Add to preorder
    if (window.gtag && this.queue.addToPreorder.length > 0) {
      window.gtag('event', 'kattintas', {
        event_category: 'elojegyzes',
      });

      if (this.isDev) console.log(`${this.queue.addToPreorder.length} Preorder additions sent`);

      this.queue.addToPreorder = [];
    }

    // Remove from preorder
    if (window.gtag && this.queue.removeFromPreorder.length > 0) {
      window.gtag('event', 'remove_from_preorder', {
        items: this.queue.removeFromPreorder,
      });

      if (this.isDev) console.log(`${this.queue.removeFromPreorder.length} Preorder removal sent`);

      this.queue.removeFromPreorder = [];
    }

    // Purchase
    if (window.gtag && this.queue.addPurchase.length > 0) {
      this.queue.addPurchase.forEach((option) => {
        window.gtag('event', 'purchase', option);
      });

      if (this.isDev) console.log(`${this.queue.addPurchase.length} purchase sent`);

      this.queue.addPurchase = [];
    }

    // Begin checkout
    if (window.gtag && this.queue.beginCheckout.length > 0) {
      window.gtag('event', 'begin_checkout', {
        items: this.queue.beginCheckout,
      });

      if (this.isDev) console.log(`${this.queue.beginCheckout.length} checkout begin sent`);

      this.queue.beginCheckout = [];
    }

    // Progress checkout
    if (window.gtag && this.queue.progressCheckout.length > 0) {
      this.queue.progressCheckout.forEach((option) => {
        window.gtag('event', 'checkout_progress', option);
      });
      // OLD VERSION
      // window.gtag('event', 'checkout_progress', {
      //   items: this.queue.progressCheckout,
      // });

    

      if (this.isDev) console.log(`${this.queue.progressCheckout.length} checkout progress sent`);

      this.queue.progressCheckout = [];
    }

    // Checkout options
    if (window.gtag && this.queue.optionsCheckout.length > 0) {
      this.queue.optionsCheckout.forEach((option) => {
        window.gtag('event', 'set_checkout_option', option);
      });



      if (this.isDev) console.log(`${this.queue.optionsCheckout.length} checkout options sent`);

      this.queue.optionsCheckout = [];
    }

    // ADD GA4 shipping info collection
    if (window.gtag && this.queue.addShippingInfo.length > 0) {
      this.queue.addShippingInfo.forEach((option) => {
        window.gtag('event', 'add_shipping_info', option);
      });    

      if (this.isDev) console.log(`${this.queue.addShippingInfo.length} checkout options sent`);

      this.queue.addShippingInfo = [];
    } 

    // ADD GA4 Payment info collection
    if (window.gtag && this.queue.addPaymentInfo.length > 0) {
      this.queue.addPaymentInfo.forEach((option) => {
        window.gtag('event', 'add_payment_info', option);
      });    

      if (this.isDev) console.log(`${this.queue.addPaymentInfo.length} checkout options sent`);

      this.queue.addPaymentInfo = [];
    } 
  }
}

export let analytics = new Analytics();