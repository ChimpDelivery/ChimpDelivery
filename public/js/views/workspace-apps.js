$(document).ready(function () {
    $('#buildModal').on('show.bs.modal', function (event) {
        // Get the button that triggered the modal
        let button = $(event.relatedTarget);

        // Extract value from the custom data-* attribute
        let projectName = button.data('project');
        let prettyProjectName = projectName.slice(0, 13) + (projectName.length > 13 ? '...' : '');
        let buildUrl = button.data('build-url');

        document.getElementById('project-button-inner').innerHTML = prettyProjectName + ' X';
        document.getElementById('build-app').action = buildUrl;
    });
});
