/**
 * @Author: Anis KASMI
 * @Date:   2019-03-23T18:48:35+02:00
 * @Email:  contact@aniskasmi.com
 * @Filename: mask.init.js
 * @Last modified by:   aniskasmi
 * @Last modified time: 2019-07-01T22:50:52+02:00
 * @Copyright: Skyreka Studio (skyreka.com)
 */



$(function(e) {
    "use strict";
    $(".date-inputmask").inputmask("yyyy-mm-dd"),
    $(".purchase-inputmask").inputmask("aaaa 9999-****"),
    $(".phonefr-inputmask").inputmask("99.99.99.99.99"),
    $(".percentage-inputmask").inputmask("99%"),
    $(".numeric-inputmask").inputmask('Regex', {regex: "^[0-9]{1,9}(\\.\\d{1,3})?$"}),
    $(".decimal-inputmask").inputmask({
        alias: "decimal"
        , radixPoint: "."
    }),

    $(".email-inputmask").inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]"
    , greedy: !1
    , onBeforePaste: function (n, a) {
        return (e = e.toLowerCase()).replace("mailto:", "")
    }
    , definitions: {
        "*": {
            validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]"
            , cardinality: 1
            , casing: "lower"
        }
    }
    })
});
