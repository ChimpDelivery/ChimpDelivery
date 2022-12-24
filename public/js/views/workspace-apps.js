$(document).ready(function () {
    $('#buildModal').on('show.bs.modal', function (event) {
        // Get the button that triggered the modal
        let button = $(event.relatedTarget);

        document.getElementById('project-button-inner').innerHTML = makePretty(button.data('project'));
        document.getElementById('build-app').action = button.data('build-url');
    });
});

function makePretty(projectName)
{
    return projectName.slice(0, 13) + (projectName.length > 13 ? '...' : '') + ' X';
}
