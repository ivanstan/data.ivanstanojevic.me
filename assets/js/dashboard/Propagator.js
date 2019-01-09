export default class Propagator {

  static getSGP4 (satellite) {
    return new Orb.SGP4({
      first_line: satellite.tle.line1,
      second_line: satellite.tle.line2
    });
  }

  static precalculate (sgp4, satellite, date, orbits) {
    let period = satellite.tle.orbitalPeriod * 1000, // ms
      timestamp = date.getTime(),
      ΔT = 3000,
      TO = Math.floor(period),
      half = Math.floor(TO / 2 * orbits),
      T1 = timestamp - half,
      T2 = timestamp + half,
      result = {};

    while (T1 < T2) {
      let time = new Date(T1);

      result[time.getTime()] = sgp4.latlng(time);

      T1 += ΔT;
    }

    return result;
  }
}
