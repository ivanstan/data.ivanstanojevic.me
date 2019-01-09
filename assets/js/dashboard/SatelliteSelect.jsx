import React from 'react';
import Select from 'react-select';
import TleService from 'tle.js';

export default class SatelliteSelect extends React.Component {

  constructor (props) {
    super(props);

    this.tle = new TleService();

    this.state = {
      value: props.value,
      options: []
    }
  }

  componentDidMount () {
    this.inputChange('');
    this.setState({
      value: this.props.value
    });
  }

  componentWillReceiveProps (nextProps, nextContext) {
    this.setState({
      value: nextProps.value
    });
  }

  handleChange (value) {
    this.setState({value: value});
    this.props.onChange(value);
  }

  inputChange (input) {
    this.tle.search(input).then((data) => {

      let options = data.map((tle) => {
        return {
          tle: tle,
          label: tle.name,
          value: tle.satelliteId
        };
      });

      this.setState({
        options: options
      });
    });
  }

  render () {
    return <Select value={this.state.value}
                   onChange={this.handleChange.bind(this)}
                   onInputChange={this.inputChange.bind(this)}
                   options={this.state.options}
                   isSearchable={true}
                   placeholder="Search satellites"
                   isMulti={this.props.multiple || false}
                   autosize={false}/>;
  }
}
