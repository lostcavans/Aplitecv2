import 'dart:convert';
import 'package:http/http.dart' as http;

class DataService {
  final String baseUrl;

  DataService(this.baseUrl);

  // Método para obtener las estaciones
  Future<List<Map<String, dynamic>>> fetchStations() async {
    final response = await http.get(Uri.parse('$baseUrl/get_stations.php'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Error al cargar estaciones');
    }
  }

  // Método para obtener los datos de UB1
  Future<List<Map<String, dynamic>>> fetchUb1Data(int stationId) async {
    final response = await http.get(Uri.parse('$baseUrl/ub1_data.php?id_est=$stationId'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Error al cargar datos UB1');
    }
  }

  // Método para obtener los datos de UB2
  Future<List<Map<String, dynamic>>> fetchUb2Data(int stationId) async {
    final response = await http.get(Uri.parse('$baseUrl/ub2_data.php?id_est=$stationId'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Error al cargar datos UB2');
    }
  }

  // Método para obtener los datos históricos
  Future<List<Map<String, dynamic>>> fetchHistoricalData(int stationId, String startDate, String endDate) async {
    final response = await http.get(Uri.parse('$baseUrl/historical_data.php?id_est=$stationId&start_date=$startDate&end_date=$endDate'));
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Error al cargar datos históricos');
    }
  }
}
