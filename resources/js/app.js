import './bootstrap';

// Core libraries (order matters!)
import jQuery from "jquery";
import jszip from 'jszip';
import pdfmake from 'pdfmake';
import DataTable from 'datatables.net-dt';
import TomSelect from 'tom-select';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';

// CSS imports
import 'tom-select/dist/css/tom-select.default.css';
import 'sweetalert2/dist/sweetalert2.min.css';

// Make jQuery globally available FIRST (critical for compatibility)
window.$ = window.jQuery = jQuery;

// DataTables extensions (import after jQuery is global)
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

// Configure DataTables with external libraries
DataTable.Buttons.jszip(jszip);
DataTable.Buttons.pdfMake(pdfmake);

// Make all libraries globally available
window.DataTable = DataTable;
window.jszip = jszip;
window.pdfMake = pdfmake;
window.TomSelect = TomSelect;
window.DateTime = DateTime;
window.Swal = Swal;
window.Alpine = Alpine;

// Configure Alpine.js
Alpine.plugin(mask);

// Custom modules (import after globals are set)
import './custom/loading-overlay';
import './custom/select-date';
import './custom/button';
import './helper/formatting';
import './reusable/modal';

// Start Alpine.js
Alpine.start();
