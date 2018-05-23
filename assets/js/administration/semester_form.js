window.addEventListener('DOMContentLoaded', () => {
  const $groupList = document.getElementById('group-list');
  const $studentList = document.getElementById('student-list');
  const $addGroup = document.getElementById('add-group');

  // ADD / DELETE GROUPS
  let groupCounter = $groupList.children.length;

  $addGroup.addEventListener('click', (e) => {
    e.preventDefault();

    if ($groupList.hasAttribute('data-empty')) {
      // Remove message telling list is empty
      $groupList.children[0].remove();

      // Remove list attribute
      $groupList.removeAttribute('data-empty');
    }

    // Create widget from prototype
    const newWidget = $groupList.getAttribute('data-prototype')
      .replace(/__group__/g, groupCounter);

    // Insert the widdget in last position
    $groupList.insertAdjacentHTML(
      'beforeend',
      $groupList.getAttribute('data-widget-container')
        .replace(/__content__/g, newWidget),
    );

    const $created = $groupList.lastChild;

    // Set default group number
    const number = 1 + ($created.previousElementSibling ?
      +$created.previousElementSibling.querySelector('[name*=number]').value : 0);

    const $number = $created.querySelector('[name*=number]');
    $number.value = number;

    // Update input's label
    $number.nextElementSibling.classList.add('active');

    // Initialize materialize select
    M.FormSelect.init($created.querySelector('select'));

    // Can't use $groupList.children.length because of the ability to delete groups
    groupCounter += 1;
  });

  $groupList.addEventListener('click', (e) => {
    e.preventDefault();

    const action = e.target.getAttribute('data-action');
    if (!action) {
      return;
    }

    if (action === 'delete') {
      // Remove the list item
      const $item = e.target.closest('.collection-item');
      const $parent = $item.parentNode;

      $item.remove();

      // Update list state
      if ($parent.children.length === 0) {
        $groupList.setAttribute('data-empty', '');
        $parent.insertAdjacentHTML(
          'afterbegin',
          `<li class="collection-item">${Translator.trans('semester.form.props.groups.empty')}</li>`,
        );
      }
    }
  });
});
