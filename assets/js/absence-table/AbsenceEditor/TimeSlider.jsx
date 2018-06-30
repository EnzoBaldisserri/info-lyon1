import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import NoUiSlider from '../../react-utils/NoUiSlider';

class TimeSlider extends PureComponent {
  static propTypes = {
    times: PropTypes.arrayOf(PropTypes.string).isRequired,
    onChange: PropTypes.func.isRequired,
  };

  static timeFormatter = {
    to: (value) => {
      const hours = Math.floor(value);
      const minutes = (value - hours) * 60;

      const padHours = hours.toString().padStart(2, '0');
      const padMinutes = minutes.toString().padStart(2, '0');

      return `${padHours}:${padMinutes}`;
    },
    from: (string) => {
      const [hours, minutes] = string.split(':', 2);

      return +hours + (+minutes / 60);
    },
  };

  constructor(props) {
    super(props);

    this.sliderContainer = React.createRef();
  }

  onUpdate = (values, handle) => {
    setTimeout(() => {
      const $time = handle === 0
        ? this.sliderContainer.current.firstElementChild
        : this.sliderContainer.current.lastElementChild;

      $time.textContent = values[handle];
    });
  };

  filterScaleHours = value => (value % 2 === 0 ? 1 : 0);

  render() {
    const { times, onChange } = this.props;

    const sliderOptions = {
      connect: true,
      step: 0.5,
      margin: 0.5,
      behaviour: 'tap-drag',
      format: TimeSlider.timeFormatter,
      pips: {
        mode: 'steps',
        density: 5,
        filter: this.filterScaleHours,
      },
      tooltips: true,
      onUpdate: this.onUpdate,
      onSet: onChange,
    };

    return (
      <div ref={this.sliderContainer} className="valign-wrapper py-3">
        <span />
        <NoUiSlider
          start={times}
          range={{ min: 7, max: 20 }}
          {...sliderOptions}
          className="mx-2 w-100"
        />
        <span />
      </div>
    );
  }
}

export default TimeSlider;
