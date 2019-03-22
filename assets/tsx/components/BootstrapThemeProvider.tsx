import * as React from 'react';
import {ThemeProvider} from 'styled-components';

const getThemeProperty = (name) => {
    return getComputedStyle(document.body).getPropertyValue(name).trim();
};

const theme = {
    'blue': getThemeProperty('--blue'),
    'indigo': getThemeProperty('--indigo'),
    'purple': getThemeProperty('--purple'),
    'pink': getThemeProperty('--pink'),
    'red': getThemeProperty('--red'),
    'orange': getThemeProperty('--orange'),
    'yellow': getThemeProperty('--yellow'),
    'green': getThemeProperty('--green'),
    'teal': getThemeProperty('--teal'),
    'cyan': getThemeProperty('--cyan'),
    'white': getThemeProperty('--white'),
    'gray': getThemeProperty('--gray'),
    'gray-dark': getThemeProperty('--gray-dark'),
    'primary': getThemeProperty('--primary'),
    'secondary': getThemeProperty('--secondary'),
    'success': getThemeProperty('--success'),
    'info': getThemeProperty('--info'),
    'warning': getThemeProperty('--warning'),
    'danger': getThemeProperty('--danger'),
    'light': getThemeProperty('--light'),
    'dark': getThemeProperty('--dark'),
    'breakpoint-xs': getThemeProperty('--breakpoint-xs'),
    'breakpoint-sm': getThemeProperty('--breakpoint-sm'),
    'breakpoint-md': getThemeProperty('--breakpoint-md'),
    'breakpoint-lg': getThemeProperty('--breakpoint-lg'),
    'breakpoint-xl': getThemeProperty('--breakpoint-xl'),
    'font-family-sans-serif': getThemeProperty('--font-family-sans-serif'),
    'font-family-monospace': getThemeProperty('--font-family-monospace')
};

export class BootstrapThemeProvider extends React.Component {

    render() {
        return <ThemeProvider theme={theme}>
            {this.props.children}
        </ThemeProvider>;
    }
}
