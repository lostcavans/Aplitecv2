import 'package:flutter/material.dart';
import 'login_screen.dart';  // Asegúrate de que esta ruta sea correcta según tu estructura de proyecto

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Flutter Login Demo',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: LoginScreen(),  // Aquí definimos la pantalla de inicio de la app
    );
  }
}