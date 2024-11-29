import 'dart:convert';
import 'package:http/http.dart' as http;

class StationData {
  final String idEst;
  final String battV;
  final String tempAmb;
  final String pBar;
  final String timestamp;
  final String precipP;
  final String rad;
  final String rh;
  final String dirV;

  StationData({
    required this.idEst,
    required this.battV,
    required this.tempAmb,
    required this.pBar,
    required this.timestamp,
    required this.precipP,
    required this.rad,
    required this.rh,
    required this.dirV,
  });

  factory StationData.fromJson(Map<String, dynamic> json) {
    return StationData(
      idEst: json['id_est'],
      battV: json['avg_BattV'],
      tempAmb: json['avg_TempAmb'],
      pBar: json['avg_Pbar'],
      timestamp: json['interval_start'],
      precipP: json['avg_PrecipP'],
      rad: json['avg_Rad'],
      rh: json['avg_RH'],
      dirV: json['avg_DirV'],
    );
  }

  static Future<List<StationData>> fetchStationData({int idEst = 2}) async {
    final url = 'http://66.94.116.235/AppIntec1.0/get_station_data.php?id_est=$idEst';
    final response = await http.get(Uri.parse(url));

    if (response.statusCode == 200) {
      List<dynamic> data = json.decode(response.body);
      return data.map((item) => StationData.fromJson(item)).toList();
    } else {
      throw Exception('Failed to load data');
    }
  }
}

class StationInfo {
  final int idEst;
  final String descr;

  StationInfo({required this.idEst, required this.descr});

  factory StationInfo.fromJson(Map<String, dynamic> json) {
    return StationInfo(
      idEst: json['id_est'],
      descr: json['Descr'],
    );
  }

  static Future<List<StationInfo>> fetchStations() async {
    final url = 'http://66.94.116.235/AppIntec1.0/get_stations.php';
    final response = await http.get(Uri.parse(url));

    if (response.statusCode == 200) {
      final jsonResponse = json.decode(response.body);
      if (jsonResponse['status'] == 'success') {
        List<dynamic> data = jsonResponse['est'];
        return data.map((item) => StationInfo.fromJson(item)).toList();
      } else {
        throw Exception('Error en el servidor: ${jsonResponse['message']}');
      }
    } else {
      throw Exception('Error al obtener las estaciones');
    }
  }
}
