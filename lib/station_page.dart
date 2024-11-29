import 'package:flutter/material.dart';
import 'package:intec_appflutter/station_data.dart';
import 'package:xml/xml.dart' as xml;
import 'dart:io';
import 'package:path_provider/path_provider.dart';

class StationPage extends StatefulWidget {
  const StationPage({super.key});

  @override
  _StationPageState createState() => _StationPageState();
}

class _StationPageState extends State<StationPage> {
  List<StationInfo> stations = [];
  int? selectedStationId;
  List<StationData> stationData = [];
  bool isLoading = false;

  @override
  void initState() {
    super.initState();
    _fetchStations();
  }

  // Método para obtener la lista de estaciones
  Future<void> _fetchStations() async {
    setState(() {
      isLoading = true;
    });
    try {
      final stationList = await StationInfo.fetchStations();
      setState(() {
        stations = stationList;
        if (stations.isNotEmpty) {
          selectedStationId = stations[0].idEst; // Selecciona la primera estación por defecto
          _fetchStationData(selectedStationId!); // Carga los datos de la estación por defecto
        }
      });
    } catch (e) {
      print('Error al obtener las estaciones: $e');
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }

  // Método para obtener datos de la estación seleccionada
  Future<void> _fetchStationData(int idEst) async {
    setState(() {
      isLoading = true;
    });
    try {
      final data = await StationData.fetchStationData(idEst: idEst);
      setState(() {
        stationData = data;
      });
    } catch (e) {
      print('Error al obtener los datos de la estación: $e');
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }

  // Generar XML de los datos de la estación
  String generateXml(List<StationData> data) {
    final builder = xml.XmlBuilder();
    builder.processing('xml', 'version="1.0" encoding="UTF-8"');

    builder.element('StationData', nest: () {
      for (var station in data) {
        builder.element('Data', nest: () {
          builder.element('BattV', nest: station.battV.toString());
          builder.element('TempAmb', nest: station.tempAmb.toString());
          builder.element('PBar', nest: station.pBar.toString());
          builder.element('PrecipP', nest: station.precipP.toString());
          builder.element('Rad', nest: station.rad.toString());
          builder.element('RH', nest: station.rh.toString());
          builder.element('DirV', nest: station.dirV.toString());
          builder.element('Timestamp', nest: station.timestamp);
        });
      }
    });

    final document = builder.buildDocument();
    return document.toXmlString(pretty: true);
  }

  // Guardar archivo XML
  Future<void> saveXmlToFile(List<StationData> data) async {
    final String xmlContent = generateXml(data);

    // Obtener la ruta del directorio de documentos
    final directory = await getApplicationDocumentsDirectory();
    final file = File('${directory.path}/station_data.xml');

    // Guardar el archivo XML
    await file.writeAsString(xmlContent);
    print('Archivo XML guardado en: ${file.path}');
    // Puedes agregar un mensaje para el usuario indicando que el archivo fue guardado.
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Seleccionar Estación'),
      ),
      body: isLoading
          ? Center(child: CircularProgressIndicator())
          : Column(
              children: [
                // DropdownButton para seleccionar estación
                if (stations.isNotEmpty)
                  DropdownButton<int>(
                    value: selectedStationId,
                    items: stations.map((station) {
                      return DropdownMenuItem<int>(
                        value: station.idEst,
                        child: Text(station.descr),
                      );
                    }).toList(),
                    onChanged: (value) {
                      if (value != null) {
                        setState(() {
                          selectedStationId = value;
                        });
                        _fetchStationData(value); // Cargar datos de la estación seleccionada
                      }
                    },
                  ),
                // Botón para descargar los datos como XML
                ElevatedButton(
                  onPressed: () {
                    if (stationData.isNotEmpty) {
                      saveXmlToFile(stationData);
                    }
                  },
                  child: Text('Descargar Datos como XML'),
                ),
                // Lista de datos de la estación seleccionada
                Expanded(
                  child: stationData.isEmpty
                      ? Center(child: Text('No hay datos para esta estación'))
                      : ListView.builder(
                          itemCount: stationData.length,
                          itemBuilder: (context, index) {
                            final data = stationData[index];
                            return Card(
                              margin: EdgeInsets.symmetric(vertical: 8, horizontal: 15),
                              child: Padding(
                                padding: const EdgeInsets.all(8.0),
                                child: Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text('BattV: ${data.battV}'),
                                    Text('TempAmb: ${data.tempAmb}'),
                                    Text('PBar: ${data.pBar}'),
                                    Text('PrecipP: ${data.precipP}'),
                                    Text('Rad: ${data.rad}'),
                                    Text('RH: ${data.rh}'),
                                    Text('DirV: ${data.dirV}'),
                                    Text('Timestamp: ${data.timestamp}'),
                                  ],
                                ),
                              ),
                            );
                          },
                        ),
                ),
              ],
            ),
    );
  }
}
