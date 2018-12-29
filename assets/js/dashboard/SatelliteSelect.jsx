import React from 'react';
import Select from 'react-select';

export default class SatelliteSelect extends React.Component {

  constructor (props) {
    super(props);

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
    fetch(`${this.props.url}?search=${input}`).then(response => response.json()).then((response) => {
      let options = response.member.map((item) => {
        item.label = item.name;
        item.value = item.satelliteId;

        return item;
      });

      this.setState({
        options: this.state.options.concat(options)
      })
    })
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
