import 'package:flutter/material.dart';
import 'map_screen.dart';  // Asegúrate de que esta ruta sea correcta según tu estructura de proyecto
import 'station_page.dart';  // Importa la nueva pantalla StationPage

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _currentIndex = 0;  // Índice de la página actual

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Home Page'),
        backgroundColor: const Color.fromARGB(255, 17, 46, 70),  // Color de fondo del AppBar en tonalidades azules
        foregroundColor: Colors.white,
        centerTitle: true,  // Centrar el título
        elevation: 4,  // Elevación para dar una sombra sutil
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(bottom: Radius.circular(30)),
        ),  // Borde redondeado en la parte inferior del AppBar
      ),
      body: _currentIndex == 0
          ? MapScreen()  // Pantalla del mapa
          : StationPage(),  // Pantalla de historial
      bottomNavigationBar: AnimatedContainer(
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeInOut,
        decoration: BoxDecoration(
          gradient: LinearGradient(
            colors: [
              const Color.fromARGB(255, 17, 46, 70),  // Azul oscuro
              const Color.fromARGB(255, 1, 51, 92),  // Azul más oscuro
            ],
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
          ),
          borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
        ),
        child: BottomNavigationBar(
          currentIndex: _currentIndex,
          onTap: (int index) {
            setState(() {
              if (index == 1) {
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context) => StationPage()),
                );
              } else {
                _currentIndex = index;  // Cambiar la pantalla actual
              }
            });
          },
          items: [
            BottomNavigationBarItem(
              icon: AnimatedIcon(
                icon: AnimatedIcons.menu_home,
                progress: AlwaysStoppedAnimation(0.5),
              ),
              label: 'Mapa',
            ),
            BottomNavigationBarItem(
              icon: Icon(Icons.history),
              label: 'Histórico',
            ),
          ],
          backgroundColor: Colors.transparent,  // Fondo transparente para que se vea el degradado
          selectedItemColor: Colors.white,
          unselectedItemColor: Colors.white70,
          elevation: 0,  // Sin sombra
        ),
      ),
    );
  }
}
