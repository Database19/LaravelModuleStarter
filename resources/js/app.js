import './bootstrap';

import jQuery from "jquery";
import jszip from 'jszip';
import pdfmake from 'pdfmake';
import DataTable from 'datatables.net-dt';
import 'datatables.net-autofill-dt';
import 'datatables.net-buttons-dt';
import 'datatables.net-buttons/js/buttons.colVis.mjs';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-colreorder-dt';
import 'datatables.net-columncontrol-dt';
import DateTime from 'datatables.net-datetime';
import 'datatables.net-fixedcolumns-dt';
import 'datatables.net-fixedheader-dt';
import 'datatables.net-keytable-dt';
import 'datatables.net-responsive-dt';
import 'datatables.net-rowgroup-dt';
import 'datatables.net-rowreorder-dt';
import 'datatables.net-scroller-dt';
import 'datatables.net-searchbuilder-dt';
import 'datatables.net-searchpanes-dt';
import 'datatables.net-select-dt';
import 'datatables.net-staterestore-dt';
import './custom/loading-overlay';
import './custom/select-date';
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import Alpine from 'alpinejs'
import mask from '@alpinejs/mask'

window.Alpine = Alpine
window.Swal = Swal;

DataTable.Buttons.jszip(jszip);
DataTable.Buttons.pdfMake(pdfmake);

window.$ = window.jQuery = jQuery;
window.DateTime = DateTime

import './custom/button';
import './helper/formatting';
Alpine.plugin(mask)
Alpine.start()


import './reusable/modal'
