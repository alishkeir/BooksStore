import { NextResponse } from 'next/server'
import url from "libs/url";
export function middleware(request) {
    url.setHost(request);
   // store.dispatch(updateSettings(settingsVars.get(url.getHost())));

    return NextResponse.next();
}
