window.addEventListener('DOMContentLoaded', () => {
  const $add = document.getElementById('add-group');
  const $list = document.querySelector($add.getAttribute('data-list'));
  let counter = $list.children.length;

  $add.addEventListener('click', (e) => {
    e.preventDefault();

    if ($list.hasAttribute('data-empty')) {
      // Remove message telling list is empty
      $list.children[0].remove();

      // Remove list attribute
      $list.removeAttribute('data-empty');
    }

    // Create widget from prototype
    const newWidget = $list.getAttribute('data-prototype')
      .replace(/__name__/g, counter);

    // Insert the widdget in last position
    $list.insertAdjacentHTML(
      'beforeend',
      $list.getAttribute('data-widget-container')
        .replace(/__content__/g, newWidget),
    );

    const $created = $list.lastChild;

    // Set default group number
    const number = 1 + ($created.previousElementSibling ?
      +$created.previousElementSibling.querySelector('[name*=number]').value : 0);

    const $number = $created.querySelector('[name*=number]');
    $number.value = number;

    // Update input's label
    $number.nextElementSibling.classList.add('active');

    // Initialize materialize select
    M.FormSelect.init($created.querySelector('select'));

    // Can't use $list.children.length because of the ability to delete groups
    counter += 1;
  });

  $list.addEventListener('click', (e) => {
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
        $list.setAttribute('data-empty', '');
        $parent.insertAdjacentHTML(
          'afterbegin',
          `<li class="collection-item">${Translator.trans('semester.form.props.groups.empty')}</li>`,
        );
      }
    }
  });
});
