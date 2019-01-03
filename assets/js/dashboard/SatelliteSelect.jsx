import React from 'react';
import Select from 'react-select';
import TleService from 'tle.js';

export default class SatelliteSelect extends React.Component {

  constructor (props) {
    super(props);

    this.tle = new TleService();

    this.state = {
      selected: props.value,
      options: []
    }
  }

  componentDidMount () {
    this.inputChange('');
    this.setState({
      selected: this.props.selected
    });
  }

  handleChange (selected) {
    this.setState({selected: selected});
    this.props.onChange(selected);
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
    return <Select value={this.state.selected}
                   onChange={this.handleChange.bind(this)}
                   onInputChange={this.inputChange.bind(this)}
                   options={this.state.options}
                   isSearchable={true}
                   placeholder="Search satellites"
                   isMulti={this.props.multiple || false}
                   autosize={false}/>;
  }
}
