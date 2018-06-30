/* eslint-disable react/no-unused-prop-types */

import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import nouislider from 'materialize-css/extras/noUiSlider/nouislider';

class NoUiSlider extends PureComponent {
    static propTypes = {
      animate: PropTypes.bool,
      animationDuration: PropTypes.number,
      behaviour: PropTypes.string,
      connect: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.bool),
        PropTypes.bool,
      ]),
      className: PropTypes.string,
      cssClasses: PropTypes.shape({
        target: PropTypes.string,
        base: PropTypes.string,
        origin: PropTypes.string,
        handle: PropTypes.string,
        handleLower: PropTypes.string,
        handleUpper: PropTypes.string,
        horizontal: PropTypes.string,
        vertical: PropTypes.string,
        background: PropTypes.string,
        connect: PropTypes.string,
        connects: PropTypes.string,
        ltr: PropTypes.string,
        rtl: PropTypes.string,
        draggable: PropTypes.string,
        drag: PropTypes.string,
        tap: PropTypes.string,
        active: PropTypes.string,
        tooltip: PropTypes.string,
        pips: PropTypes.string,
        pipsHorizontal: PropTypes.string,
        pipsVertical: PropTypes.string,
        marker: PropTypes.string,
        markerHorizontal: PropTypes.string,
        markerVertical: PropTypes.string,
        markerNormal: PropTypes.string,
        markerLarge: PropTypes.string,
        markerSub: PropTypes.string,
        value: PropTypes.string,
        valueHorizontal: PropTypes.string,
        valueVertical: PropTypes.string,
        valueNormal: PropTypes.string,
        valueLarge: PropTypes.string,
        valueSub: PropTypes.string,
      }),
      cssPrefix: PropTypes.string,
      direction: PropTypes.oneOf(['ltr', 'rtl']),
      disabled: PropTypes.bool,
      format: PropTypes.shape({
        from: PropTypes.func,
        to: PropTypes.func,
      }),
      limit: PropTypes.number,
      margin: PropTypes.number,
      onChange: PropTypes.func,
      onEnd: PropTypes.func,
      onSet: PropTypes.func,
      onSlide: PropTypes.func,
      onStart: PropTypes.func,
      onUpdate: PropTypes.func,
      orientation: PropTypes.oneOf(['horizontal', 'vertical']),
      padding: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.number),
        PropTypes.number,
      ]),
      pips: PropTypes.shape({
        mode: PropTypes.string,
        values: PropTypes.any,
        stepped: PropTypes.bool,
        density: PropTypes.number,
        filter: PropTypes.func,
      }),
      range: PropTypes.shape({
        min: PropTypes.oneOfType([
          PropTypes.arrayOf(PropTypes.number),
          PropTypes.number,
        ]),
        max: PropTypes.oneOfType([
          PropTypes.arrayOf(PropTypes.number),
          PropTypes.number,
        ]),
      }).isRequired,
      snap: PropTypes.bool,
      start: PropTypes.arrayOf(PropTypes.any).isRequired,
      step: PropTypes.number,
      style: PropTypes.object, // eslint-disable-line react/forbid-prop-types
      tooltips: PropTypes.oneOfType([
        PropTypes.bool,
        PropTypes.arrayOf(PropTypes.oneOfType([
          PropTypes.bool,
          PropTypes.shape({
            to: PropTypes.func,
          }),
        ])),
      ]),
    };

    static defaultProps = {
      animate: true,
      animationDuration: 300,
      behaviour: 'tap',
      connect: false,
      className: undefined,
      cssClasses: undefined,
      cssPrefix: 'noUi-',
      direction: 'ltr',
      disabled: false,
      format: undefined,
      limit: undefined,
      margin: 0,
      onChange: null,
      onEnd: null,
      onSet: null,
      onSlide: null,
      onStart: null,
      onUpdate: null,
      orientation: 'horizontal',
      padding: 0,
      pips: null,
      snap: false,
      step: 0,
      style: undefined,
      tooltips: false,
    };

    constructor(props) {
      super(props);

      this.sliderContainer = React.createRef();
    }

    componentDidMount() {
      this.updateDisabled();
      this.createSlider();
    }

    componentDidUpdate() {
      this.updateDisabled();
      this.slider.destroy();
      this.createSlider();
    }

    componentWillUnmount() {
      this.slider.destroy();
    }

    updateDisabled = () => {
      if (this.props.disabled) {
        this.sliderContainer.current.setAttribute('disabled', true);
      } else {
        this.sliderContainer.current.removeAttribute('disabled');
      }
    };

    createSlider = () => {
      this.slider = nouislider.create(this.sliderContainer.current, { ...this.props });

      if (this.props.onUpdate) {
        this.slider.on('update', this.props.onUpdate);
      }

      if (this.props.onChange) {
        this.slider.on('change', this.props.onChange);
      }

      if (this.props.onSlide) {
        this.slider.on('slide', this.props.onSlide);
      }

      if (this.props.onStart) {
        this.slider.on('start', this.props.onStart);
      }

      if (this.props.onEnd) {
        this.slider.on('end', this.props.onEnd);
      }

      if (this.props.onSet) {
        this.slider.on('set', this.props.onSet);
      }
    };

    render() {
      const { style, className } = this.props;

      return (
        <div
          ref={this.sliderContainer}
          className={className}
          style={style}
        />
      );
    }
}

export default NoUiSlider;
