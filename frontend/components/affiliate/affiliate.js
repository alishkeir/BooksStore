import { useEffect } from 'react';
import { Cookies } from 'react-cookie';

export default function Affiliate() {
    useEffect(() => {
        // affiliate code
        let affiliateCode = new URLSearchParams(window.location.search).get('aff');
        if (affiliateCode) {
            const cookies = new Cookies();
            cookies.set('temporary_affiliate_code', affiliateCode);
        }
    }, []);
    return null;
}