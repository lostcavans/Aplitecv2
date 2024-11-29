import 'dart:convert';
import 'package:http/http.dart' as http;

Future<Map<String, dynamic>> registerUser(Map<String, String> userData) async {
  final response = await http.post(
    Uri.parse('http://127.0.0.1/INTEC_APPFLUTTER/lib/reg.php'),  // Cambia a tu URL
    headers: {'Content-Type': 'application/json'},
    body: json.encode(userData),
  );

  if (response.statusCode == 200) {
    return json.decode(response.body);
  } else {
    throw Exception('Failed to register user');
  }
}
