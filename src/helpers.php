<?php

if (!function_exists('shipit_regions')) {
    function shipit_regions($asArray = false)
    {
        return ShipIt::getRegions($asArray);
    }
}

if (!function_exists('shipit_communes')) {
    function shipit_communes($asArray = false)
    {
        return ShipIt::getCommunes($asArray);
    }
}

if (!function_exists('shipit_shippings')) {
    function shipit_shippings($date = null)
    {
        return ShipIt::getAllShippings($date);
    }
}

if (!function_exists('shipit_shipping')) {
    function shipit_shipping($id)
    {
        return ShipIt::getShipping($id);
    }
}

if (!function_exists('shipit_quotation')) {
    function shipit_quotation(\Kattatzu\ShipIt\QuotationRequest $request)
    {
        return ShipIt::getQuotation($request);
    }
}

if (!function_exists('shipit_best_quotation')) {
    function shipit_best_quotation(\Kattatzu\ShipIt\QuotationRequest $request)
    {
        return ShipIt::getBestQuotation($request);
    }
}

if (!function_exists('shipit_economic_quotation')) {
    function shipit_economic_quotation(\Kattatzu\ShipIt\QuotationRequest $request)
    {
        return ShipIt::getEconomicQuotation($request);
    }
}

if (!function_exists('shipit_send_shipping')) {
    function shipit_send_shipping(\Kattatzu\ShipIt\ShippingRequest $request)
    {
        return ShipIt::requestShipping($request);
    }
}

if (!function_exists('shipit_tracking_url')) {
    function shipit_tracking_url($provider, $trackingNumber)
    {
        return ShipIt::getTrackingUrl($provider, $trackingNumber);
    }
}
