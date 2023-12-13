export default class url {
    static host;
    static setHost(request){
        if(typeof request !== "undefined" && request !== null){
            if(typeof request.headers?.host !== "undefined") {
                url.host = request.headers?.host;
            }else if(typeof request.headers?.get === "function") {
                url.host = request.headers?.get('host');
            }
        }
        if(typeof window !== "undefined"){
            url.host = window.location.host;
        }
        //store.dispatch(setHost(host));
    }
    static getHost() {
        if(typeof window !== "undefined"){
            return window.location.host;
        }
        return url.host;
    }
}
