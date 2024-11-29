import 'dart:async';
import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: MapScreen(),
      theme: ThemeData(
        primarySwatch: Colors.blue, // Cambié el color principal a azul
        visualDensity: VisualDensity.adaptivePlatformDensity,
      ),
    );
  }
}

class MapScreen extends StatefulWidget {
  const MapScreen({super.key});

  @override
  _MapScreenState createState() => _MapScreenState();
}

class _MapScreenState extends State<MapScreen> {
  List<Marker> _markers = [];
  final MapController _mapController = MapController();
  Timer? _timer;

  @override
  void initState() {
    super.initState();
    _fetchStations();
    _timer = Timer.periodic(const Duration(minutes: 10), (Timer t) => _fetchStations());
  }

  Future<void> _fetchStations() async {
    final response = await http.get(Uri.parse('http://66.94.116.235/AppIntec1.0/get_stations.php'));

    if (response.statusCode == 200) {
      final Map<String, dynamic> data = jsonDecode(response.body);

      if (data['status'] == 'success') {
        List<Marker> markers = (data['est'] as List).map((station) {
          final double lat = double.tryParse(station['Latitud'].toString()) ?? 0.0;
          final double lng = double.tryParse(station['Longitud'].toString()) ?? 0.0;

          return Marker(
            width: 80.0,
            height: 80.0,
            point: LatLng(lat, lng),
            child: IconButton(
              icon: const Icon(Icons.location_pin, color: Colors.blue), // Cambié el color del pin a azul
              onPressed: () => _showStationDetails(station['id_est'].toString()),
            ),
          );
        }).toList();

        setState(() {
          _markers = markers;
        });
      }
    } else {
      throw Exception('Failed to load stations');
    }
  }

  Future<void> _showStationDetails(String stationId) async {
    final response = await http.get(Uri.parse('http://66.94.116.235/AppIntec1.0/get_station_details.php?id_est=$stationId'));

    if (response.statusCode == 200) {
      final Map<String, dynamic> data = jsonDecode(response.body);

      if (data['status'] == 'success' && data['station'] != null) {
        final station = data['station'];

        showDialog(
          context: context,
          builder: (BuildContext context) {
            return AlertDialog(
              backgroundColor: Colors.transparent,
              contentPadding: EdgeInsets.zero,
              content: Container(
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(15.0),
                  gradient: LinearGradient(
                    colors: [const Color.fromARGB(255, 17, 46, 70), const Color.fromARGB(255, 1, 51, 92)], // Fondo en tonos azules
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
                padding: const EdgeInsets.all(16.0),
                child: SingleChildScrollView(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: <Widget>[
                      Center(
                        child: Text(
                          'Detalles de la Estación',
                          style: TextStyle(
                            fontSize: 28, // Tamaño de texto más grande
                            fontWeight: FontWeight.bold,
                            color: Colors.white,
                          ),
                        ),
                      ),
                      const SizedBox(height: 10),
                      _buildDetailRow(Icons.info_outline, Colors.white, 'Estación: ${station['Descr']}'),
                      _buildDivider(),
                      _buildDetailRow(Icons.battery_full, Colors.lightBlueAccent, 'BattV: ${station['BattV']} V'),
                      _buildDivider(),
                      _buildDetailRow(Icons.thermostat, Colors.blueAccent, 'TempAmb: ${station['TempAmb']} °C'),
                      _buildDivider(),
                      _buildDetailRow(Icons.cloud, Colors.blue, 'Pbar: ${station['Pbar']} hPa'),
                      _buildDivider(),
                      _buildDetailRow(Icons.umbrella, Colors.lightBlue, 'Precipitación: ${station['PrecipP']} mm'),
                      _buildDivider(),
                      _buildDetailRow(Icons.wb_sunny, Colors.amber, 'Radiación: ${station['Rad']} W/m²'),
                      _buildDivider(),
                      _buildDetailRow(Icons.water, Colors.lightBlueAccent, 'Humedad Relativa: ${station['RH']} %'),
                      _buildDivider(),
                      _buildDetailRow(Icons.arrow_forward, Colors.blueAccent, 'Dirección del Viento: ${station['DirV']}°'),
                      _buildDivider(),
                      _buildDetailRow(Icons.access_time, Colors.white, 'Última actualización: ${station['latest_timestamp']}'),
                      const SizedBox(height: 20),
                      Center(
                        child: TextButton(
                          onPressed: () {
                            Navigator.of(context).pop();
                          },
                          style: TextButton.styleFrom(
                            backgroundColor: Colors.white,
                            padding: const EdgeInsets.symmetric(horizontal: 25.0, vertical: 10.0),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10.0),
                            ),
                          ),
                          child: const Text(
                            'Cerrar',
                            style: TextStyle(color: Colors.blue, fontSize: 16),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            );
          },
        );
      }
    } else {
      throw Exception('Failed to load station details');
    }
  }

  Widget _buildDetailRow(IconData icon, Color iconColor, String text) {
    return Row(
      children: [
        Icon(icon, color: iconColor, size: 30),
        const SizedBox(width: 8),
        Flexible(child: Text(text, style: const TextStyle(color: Colors.white, fontSize: 18))) // Aumenté el tamaño de la fuente
      ],
    );
  }

  Divider _buildDivider() {
    return const Divider(color: Colors.white, height: 15);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: FlutterMap(
        mapController: _mapController,
        options: MapOptions(
          initialCenter: LatLng(-16.5004, -68.1238),
          initialZoom: 6.0,
          maxZoom: 18.0,
          minZoom: 4.0,
        ),
        children: [
          TileLayer(
            urlTemplate: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            userAgentPackageName: 'com.example.app',
          ),
          MarkerLayer(markers: _markers),
        ],
      ),
    );
  }

  @override
  void dispose() {
    _timer?.cancel();
    super.dispose();
  }
}
