import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:provider/provider.dart';

import '../constants/app_url.dart';
import 'local_storage_service.dart';
import '../../features/auth/presentation/providers/auth_provider.dart';

// Progress model for tracking preload status
class PreloadProgress {
  final String currentTask;
  final double progress;
  final List<String> completedTasks;

  PreloadProgress({
    required this.currentTask,
    required this.progress,
    required this.completedTasks,
  });
}

class DataPreloaderService {
  // Progress notifier for UI updates
  static final ValueNotifier<PreloadProgress> progressNotifier = ValueNotifier<PreloadProgress>(
    PreloadProgress(
      currentTask: 'Initializing...',
      progress: 0.0,
      completedTasks: [],
    ),
  );

  static bool _isPreloading = false;
  static bool _preloadComplete = false;

  static bool get isPreloading => _isPreloading;
  static bool get preloadComplete => _preloadComplete;

  // Exact school levels
  static const List<String> SCHOOL_LEVELS = ['ECE', 'Primary', 'JHS', 'SHS'];

  // Main preload method
  static Future<void> preloadAllData(BuildContext context) async {
    if (_isPreloading) return;

    _isPreloading = true;
    _preloadComplete = false;

    try {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);

      if (!authProvider.isAuthenticated || authProvider.token == null) {
        debugPrint('User not authenticated, skipping preload');
        _isPreloading = false;
        return;
      }

      final headers = authProvider.getAuthHeaders();
      final token = authProvider.token!;
      final userId = authProvider.userId;

      // Update progress - Starting
      progressNotifier.value = PreloadProgress(
        currentTask: 'Loading user profile...',
        progress: 0.05,
        completedTasks: [],
      );

      // Load user data FIRST - for offline login
      await _preloadUserData(headers, token, userId);

      progressNotifier.value = PreloadProgress(
        currentTask: 'Loading school data...',
        progress: 0.2,
        completedTasks: ['User profile loaded'],
      );

      // Load school data
      await _preloadSchoolData(headers);

      progressNotifier.value = PreloadProgress(
        currentTask: 'Loading assessment data...',
        progress: 0.35,
        completedTasks: ['User profile loaded', 'School data loaded'],
      );

      // Load assessment data
      await _preloadAssessmentData(headers);

      progressNotifier.value = PreloadProgress(
        currentTask: 'Loading grades and subjects...',
        progress: 0.5,
        completedTasks: [
          'User profile loaded',
          'School data loaded',
          'Assessment data loaded'
        ],
      );

      // Load level-based data
      await _preloadLevelBasedData(headers);

      progressNotifier.value = PreloadProgress(
        currentTask: 'Loading classroom data...',
        progress: 0.65,
        completedTasks: [
          'User profile loaded',
          'School data loaded',
          'Assessment data loaded',
          'Grades & subjects loaded'
        ],
      );

      // Load classroom data
      await _preloadClassroomData(headers);

      progressNotifier.value = PreloadProgress(
        currentTask: 'Loading questions...',
        progress: 0.8,
        completedTasks: [
          'User profile loaded',
          'School data loaded',
          'Assessment data loaded',
          'Grades & subjects loaded',
          'Classroom data loaded'
        ],
      );

      // Load questions
      await _preloadQuestions(headers);

      // Complete
      _preloadComplete = true;
      progressNotifier.value = PreloadProgress(
        currentTask: 'Complete!',
        progress: 1.0,
        completedTasks: [
          'User profile loaded',
          'School data loaded',
          'Assessment data loaded',
          'Grades & subjects loaded',
          'Classroom data loaded',
          'Questions loaded'
        ],
      );

      debugPrint('✅ All data preloaded successfully!');

    } catch (e) {
      debugPrint('❌ Error during preload: $e');
      progressNotifier.value = PreloadProgress(
        currentTask: 'Error during preload',
        progress: 0.0,
        completedTasks: [],
      );
    } finally {
      _isPreloading = false;
    }
  }

  static Future<void> _preloadSchoolData(Map<String, String> headers) async {
    try {
      await Future.wait([
        _fetchAndCache('${AppUrl.url}/counties', headers, 'counties'),
        _fetchAndCache('${AppUrl.url}/districts', headers, 'all_districts'),
        _fetchAndCache('${AppUrl.url}/school-levels', headers, 'levels'),
        _fetchAndCache('${AppUrl.url}/school-types', headers, 'types'),
        _fetchAndCache('${AppUrl.url}/school-ownerships', headers, 'ownerships'),
      ]);
    } catch (e) {
      debugPrint('School data preload error: $e');
    }
  }

  static Future<void> _preloadAssessmentData(Map<String, String> headers) async {
    try {
      await Future.wait([
        _fetchAndCache('${AppUrl.url}/positions', headers, 'positions'),
        _fetchAndCache('${AppUrl.url}/fees', headers, 'fees'),
      ]);
    } catch (e) {
      debugPrint('Assessment data preload error: $e');
    }
  }

  static Future<void> _preloadLevelBasedData(Map<String, String> headers) async {
    try {
      final List<Future> tasks = [];
      for (var level in SCHOOL_LEVELS) {
        tasks.add(_fetchAndCache(
            '${AppUrl.url}/level/grades?level=$level',
            headers,
            'grades_${level.toLowerCase()}'
        ));
        tasks.add(_fetchAndCache(
            '${AppUrl.url}/level/subjects?level=$level',
            headers,
            'subjects_${level.toLowerCase()}'
        ));
      }
      await Future.wait(tasks);
    } catch (e) {
      debugPrint('Level data preload error: $e');
    }
  }

  static Future<void> _preloadClassroomData(Map<String, String> headers) async {
    try {
      await _fetchAndCache(
          '${AppUrl.url}/questions?cat=Classroom Observation',
          headers,
          'classroom_questions'
      );
    } catch (e) {
      debugPrint('Classroom data preload error: $e');
    }
  }

  // UPDATED: Enhanced user data preloading for offline login
  static Future<void> _preloadUserData(Map<String, String> headers, String token, int userId) async {
    try {
      // Preload user profile
      final userResponse = await http.get(
        Uri.parse('${AppUrl.url}/user'),
        headers: headers,
      );

      if (userResponse.statusCode == 200) {
        final userData = jsonDecode(userResponse.body);
        if (userData is Map) {
          // Extract user data from response
          Map<String, dynamic> user;
          if (userData.containsKey('user') && userData['user'] is Map) {
            user = Map<String, dynamic>.from(userData['user']);
          } else {
            user = Map<String, dynamic>.from(userData);
          }

          // Save user data for offline login
          await LocalStorageService.saveToCache('offline_user', user);
          debugPrint('✅ User profile cached for offline login');
        }
      }

      // Preload user's schools
      await _fetchAndCache('${AppUrl.url}/my-schools', headers, 'my_schools');

      // Preload user's county
      await _fetchUserCounty(headers);

      // Preload user's school count for code generation
      if (userId > 0) {
        try {
          final schoolCountResponse = await http.get(
            Uri.parse('${AppUrl.url}/user/school-count?user_id=$userId'),
            headers: headers,
          );
          if (schoolCountResponse.statusCode == 200) {
            final countData = jsonDecode(schoolCountResponse.body);
            int schoolCount = 0;
            if (countData is int) {
              schoolCount = countData;
            } else if (countData is Map && countData.containsKey('count')) {
              schoolCount = countData['count'] as int? ?? 0;
            }
            await LocalStorageService.saveToCache('user_school_count', schoolCount);
            debugPrint('✅ User school count cached: $schoolCount');
          }
        } catch (e) {
          debugPrint('School count fetch error: $e');
        }
      }

    } catch (e) {
      debugPrint('User data preload error: $e');
    }
  }

  static Future<void> _preloadQuestions(Map<String, String> headers) async {
    final categories = [
      'Document check',
      'Additional data on school documentation',
      'School Physical Infrastructure',
      'Additional data on school infrastructure',
      'School Leadership',
      'Parents',
      'Students',
      'Textbooks',
    ];

    final cacheKeys = [
      'document_check_questions',
      'additional_document_questions',
      'infrastructure_questions',
      'additional_infrastructure_questions',
      'leadership_questions',
      'parent_questions',
      'student_questions',
      'textbooks_questions',
    ];

    try {
      final List<Future> tasks = [];
      for (int i = 0; i < categories.length; i++) {
        tasks.add(_fetchAndCache(
            '${AppUrl.url}/questions?cat=${Uri.encodeComponent(categories[i])}',
            headers,
            cacheKeys[i]
        ));
      }
      await Future.wait(tasks);
    } catch (e) {
      debugPrint('Questions preload error: $e');
    }
  }

  static Future<void> _fetchAndCache(String url, Map<String, String> headers, String cacheKey) async {
    try {
      final response = await http.get(Uri.parse(url), headers: headers);
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        final list = _extractListFromResponse(data);
        if (list.isNotEmpty) {
          await LocalStorageService.saveToCache(cacheKey, list);
        }
      }
    } catch (e) {
      debugPrint('Fetch error for $url: $e');
    }
  }

  static Future<void> _fetchUserCounty(Map<String, String> headers) async {
    try {
      final response = await http.get(
        Uri.parse('${AppUrl.url}/counties'),
        headers: headers,
      );
      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        String? county;
        if (data is String) {
          county = data;
        } else if (data is Map && data.containsKey('county')) {
          county = data['county'] as String?;
        }
        if (county != null) {
          await LocalStorageService.saveToCache('user_county', county);
          debugPrint('✅ User county cached: $county');
        }
      }
    } catch (e) {
      debugPrint('User county fetch error: $e');
    }
  }

  static List<dynamic> _extractListFromResponse(dynamic data) {
    if (data is List) return data;
    if (data is Map && data.containsKey('data') && data['data'] is List) {
      return data['data'];
    }
    return [];
  }

  // NEW: Helper method to get cached user data for offline login
  static Map<String, dynamic>? getCachedUser() {
    try {
      final data = LocalStorageService.getFromCache('offline_user');
      if (data != null && data is Map) {
        return Map<String, dynamic>.from(data);
      }
    } catch (e) {
      debugPrint('Error getting cached user: $e');
    }
    return null;
  }

  // NEW: Helper method to get cached user county
  static String? getCachedUserCounty() {
    try {
      final data = LocalStorageService.getFromCache('user_county');
      if (data != null && data is String) {
        return data;
      }
    } catch (e) {
      debugPrint('Error getting cached user county: $e');
    }
    return null;
  }

  // NEW: Helper method to get cached user school count
  static int getCachedUserSchoolCount() {
    try {
      final data = LocalStorageService.getFromCache('user_school_count');
      if (data != null && data is int) {
        return data;
      }
    } catch (e) {
      debugPrint('Error getting cached user school count: $e');
    }
    return 0;
  }

  // FIXED: Public methods for accessing cached data with proper type casting
  static List<Map<String, dynamic>> getCachedData(String cacheKey) {
    try {
      final data = LocalStorageService.getFromCache(cacheKey);
      if (data != null && data is List) {
        return data.map((item) {
          if (item is Map) {
            // Safely convert Map<dynamic, dynamic> to Map<String, dynamic>
            return Map<String, dynamic>.from(item);
          }
          return <String, dynamic>{};
        }).toList();
      }
    } catch (e) {
      debugPrint('Error getting cached data for $cacheKey: $e');
    }
    return [];
  }

  static List<Map<String, dynamic>> getGradesForLevel(String level) {
    return getCachedData('grades_${level.toLowerCase()}');
  }

  static List<Map<String, dynamic>> getSubjectsForLevel(String level) {
    return getCachedData('subjects_${level.toLowerCase()}');
  }

  static List<String> getAvailableLevels() => SCHOOL_LEVELS;

  static void resetPreloadStatus() {
    _preloadComplete = false;
    _isPreloading = false;
    progressNotifier.value = PreloadProgress(
      currentTask: 'Initializing...',
      progress: 0.0,
      completedTasks: [],
    );
  }
}
