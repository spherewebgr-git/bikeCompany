document.addEventListener('DOMContentLoaded', function () {
    var sidenavElems = document.querySelectorAll('.sidenav');
    var sidenavInstances = M.Sidenav.init(sidenavElems, {
        edge: 'left'
    });
});
