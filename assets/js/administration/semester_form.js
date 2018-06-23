window.addEventListener('DOMContentLoaded', () => {
  const $groupList = document.getElementById('group-list');
  const $studentList = document.getElementById('student-list');
  const $addGroup = document.getElementById('add-group');

  const addToStudentList = (student) => {
    const $new = document.createElement('li');
    $new.classList.add('collection-item');
    $new.draggable = true;
    $new.setAttribute('data-student-id', student.id.toString());
    $new.textContent = student.name;

    const $nextSibling = Array.from($studentList.children).find($stud =>
      $stud.textContent.trim().localeCompare(student.name) === 1);

    // If next sibling is null, insert at the end
    $studentList.insertBefore($new, $nextSibling);
  };

  // ADD GROUP
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
      +$created.previousElementSibling.querySelector('[name$="[number]"]').value : 0);

    const $number = $created.querySelector('[name$="[number]"]');
    $number.value = number;

    // Update input's label
    $number.nextElementSibling.classList.add('active');

    // Initialize materialize select
    M.FormSelect.init($created.querySelector('select'));

    // Can't use $groupList.children.length because of the ability to delete groups
    groupCounter += 1;
  });

  // DELETE GROUP
  $groupList.addEventListener('click', (e) => {
    const $actionHolder = e.target.closest('[data-action]');
    if (!$actionHolder) {
      return;
    }

    const action = $actionHolder.getAttribute('data-action');

    if (action === 'delete') {
      // Find the group item
      const $item = e.target.closest('li');

      // Move students to the 'no-group' list
      const $students = $item.querySelectorAll('.collection-item');
      $students.forEach(($student) => {
        const student = {
          id: $student.querySelector('[name$="[id]"]').value,
          name: $student.textContent.trim(),
        };

        addToStudentList(student);
      });

      // Remove the group item
      $item.remove();

      // Update list state
      if ($groupList.children.length === 0) {
        $groupList.setAttribute('data-empty', '');
        $groupList.insertAdjacentHTML(
          'afterbegin',
          `<li class="collection-item">${Translator.trans('semester.form.props.groups.empty')}</li>`,
        );
      }
    }
  });

  // STUDENTS DRAG AND DROP
  let draggedElement = null;

  $groupList.addEventListener('dragstart', (e) => {
    draggedElement = e.target;

    const student = {
      id: draggedElement.querySelector('[name$="[id]"]').value,
      name: draggedElement.textContent.trim(),
    };

    e.dataTransfer.setData('text/plain', JSON.stringify(student));
  });

  $studentList.addEventListener('dragstart', (e) => {
    draggedElement = e.target;

    const student = {
      id: draggedElement.getAttribute('data-student-id'),
      name: draggedElement.textContent.trim(),
    };

    e.dataTransfer.setData('text/plain', JSON.stringify(student));
  });

  document.addEventListener('dragover', (e) => {
    if (e.target.closest('.droppable') !== null) {
      e.preventDefault();
    }
  });

  $groupList.addEventListener('drop', (e) => {
    const data = JSON.parse(e.dataTransfer.getData('text/plain'));

    const $list = e.target.closest('.collection');
    const studentCounter = +$list.getAttribute('data-counter');

    const $newWidget = $list.getAttribute('data-prototype')
      .replace(/__student__/g, studentCounter);

    const $nextSibling = Array.from($list.children)
      .filter($li => $li.classList.contains('collection-item'))
      .find($stud => $stud.textContent.trim().localeCompare(data.name) === 1);

    let $created;
    if ($nextSibling) {
      $nextSibling.insertAdjacentHTML(
        'beforebegin',
        $list.getAttribute('data-widget-container')
          .replace(/__content__/g, $newWidget),
      );
      $created = $nextSibling.previousElementSibling;
    } else {
      $list.insertAdjacentHTML(
        'beforeend',
        $list.getAttribute('data-widget-container')
          .replace(/__content__/g, $newWidget),
      );
      $created = $list.lastElementChild;
    }

    $created.querySelector('[name$="[id]"]').value = data.id;
    $created.querySelector('[name$="[fullname]"]').textContent = data.name;

    // If student was in a group
    if (draggedElement.closest('#group-list') !== null) {
      const $originalGroup = draggedElement.closest('.collection');
      const originalGroupCounter = +$originalGroup.getAttribute('data-counter');
      $originalGroup.setAttribute('data-counter', originalGroupCounter - 1);
    }

    $list.setAttribute('data-counter', studentCounter + 1);

    draggedElement.remove();
  });

  $studentList.addEventListener('drop', (e) => {
    const data = JSON.parse(e.dataTransfer.getData('text/plain'));

    addToStudentList(data);

    draggedElement.remove();
  });

  document.addEventListener('dragend', () => {
    draggedElement = null;
  });
});
