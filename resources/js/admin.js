import './data-tables.js';
import './dataTables.select.min.js';
import './jquery.dataTables.min.js';

document.addEventListener('DOMContentLoaded', function () {
    var sidenavElems = document.querySelectorAll('.sidenav');
    var sidenavInstances = M.Sidenav.init(sidenavElems, {
        edge: 'left'
    });
});
