<?php

if (!function_exists('shipit_regions')) {
    function shipit_regions()
    {
        return ShipIt::getRegions();
    }
}

if (!function_exists('shipit_communes')) {
    function shipit_communes()
    {
        return ShipIt::getCommunes();
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
    function shipit_quotation(QuotationRequest $request)
    {
        return ShipIt::getQuotation($request);
    }
}

if (!function_exists('shipit_best_quotation')) {
    function shipit_best_quotation(QuotationRequest $request)
    {
        return ShipIt::getBestQuotation($request);
    }
}

if (!function_exists('shipit_economic_quotation')) {
    function shipit_economic_quotation(QuotationRequest $request)
    {
        return ShipIt::getEconomicQuotation($request);
    }
}

if (!function_exists('shipit_send_shipping')) {
    function shipit_send_shipping(ShippingRequest $request)
    {
        return ShipIt::requestShipping($request);
    }
}