import 'dart:io';
import 'package:path_provider/path_provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:image_picker/image_picker.dart';

class ImageHelper {
  static const String _profileImageKey = 'profile_image_path';

  static Future<String?> getProfileImagePath() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_profileImageKey);
  }

  static Future<void> saveProfileImagePath(String path) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_profileImageKey, path);
  }

  static Future<File?> pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);

    if (pickedFile != null) {
      final directory = await getApplicationDocumentsDirectory();
      final name = DateTime.now().millisecondsSinceEpoch.toString();
      final File imageFile = File('${directory.path}/$name.jpg');

      // Copy file yang dipilih ke directory aplikasi
      await File(pickedFile.path).copy(imageFile.path);
      await saveProfileImagePath(imageFile.path);

      return imageFile;
    }
    return null;
  }
}
