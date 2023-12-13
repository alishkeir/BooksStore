import url from "@libs/url";
import settingsVars from "@vars/settingsVars";
let settings = settingsVars.get(url.getHost());

let token = "";

if(settings.key === "ALOMGYAR"){
  token = process.env.NEXT_PUBLIC_FACEBOOK_PIXEL_ID_ALOMGYAR;
}else if(settings.key === "OLCSOKONYVEK"){
  token = process.env.NEXT_PUBLIC_FACEBOOK_PIXEL_ID_OLCSOKONYVEK;
}else if(settings.key === "NAGYKER"){
  token = process.env.NEXT_PUBLIC_FACEBOOK_PIXEL_ID_NAGYKER;
}
export const FB_PIXEL_ID = token;

export const pageview = () => {
  window.fbq('track', 'PageView');
};

var _gravity = _gravity || [];

// https://developers.facebook.com/docs/facebook-pixel/advanced/
class Yuspify {
  constructor() {
    this.isDev = process.env.NODE_ENV === 'development';
  }

  addToCart(id, price) {
    if(!this.isDev) {
      _gravity.push({
        type: "event",
        eventType: "ADD_TO_CART",
        itemId: id.toString(),
        unitPrice: price.toString(),
        quantity: "1",
      });
    }
  }

  removeFromCart(id, price) {
    if(!this.isDev) {
      _gravity.push({
        type: "event",
        eventType: "REMOVE_FROM_CART",
        itemId: id.toString(),
        unitPrice: price.toString(),
        quantity: "1",
      });
    }
  }

  buyEvent(itemId, orderId, price) {
    _gravity.push({
      type: "event",
      eventType: "BUY",
      itemId: itemId.toString(),
      unitPrice: price.toString(),
      quantity: "1",
      orderId: orderId.toString()
    });
  }

  addToFavorites(id) {
    if(!this.isDev) {
      _gravity.push({
        type: "event",
        eventType: "ADD_TO_FAVORITES",
        itemId: id.toString(),
        quantity: "1",
      });
    }
  }

  RemoveFromFavorites(id) {
    if(!this.isDev) {
      _gravity.push({
        type: "event",
        eventType: "REMOVE_FROM_FAVORITES",
        itemId: id.toString(),
        quantity: "1",
      });
    }
  }
}

export let yuspify = new Yuspify();
