import React from 'react';
import PropTypes from 'prop-types';

import style from './StudentSummary.scss';

const StudentSummary = (props) => {
  const { open, student, toggle } = props;

  if (!open) {
    return null;
  }

  return (
    <div className={`${style.popup} z-depth-2`}>
      <span
        className={`${style['popup-close']} material-icons`}
        onClick={toggle}
        onKeyPress={toggle}
        role="button"
        tabIndex={0}
      />
      <div className={style['popup-content']}>
        TODO: Ask those datas to the server (student: {student.username})
      </div>
    </div>
  );
};

StudentSummary.defaultProps = {
  open: false,
};

StudentSummary.propTypes = {
  open: PropTypes.bool,
  student: PropTypes.shape().isRequired,
  toggle: PropTypes.func.isRequired,
};

export default StudentSummary;
