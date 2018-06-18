import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';

class Loader extends PureComponent {
  static propTypes = {
    color: PropTypes.string,
    width: PropTypes.number,
    height: PropTypes.number,
  }

  static defaultProps = {
    color: 'rgba(204, 204, 204)',
    width: 64,
    height: 64,
  }

  render() {
    const {
      color,
      width,
      height,
    } = this.props;

    return (
      <div className="center-block" style={{ width, height, fill: color }}>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
          <path opacity=".25" d="M16 0 A16 16 0 0 0 16 32 A16 16 0 0 0 16 0 M16 4 A12 12 0 0 1 16 28 A12 12 0 0 1 16 4" />
          <path d="M16 0 A16 16 0 0 1 32 16 L28 16 A12 12 0 0 0 16 4z">
            <animateTransform attributeName="transform" type="rotate" from="0 16 16" to="360 16 16" dur="0.8s" repeatCount="indefinite" />
          </path>
        </svg>
      </div>
    );
  }
}

export default Loader;
