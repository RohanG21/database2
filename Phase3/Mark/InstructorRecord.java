package com.database.db2;

import android.app.AlertDialog;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.ListView;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class InstructorRecord extends AppCompatActivity {
    ListView courseRecords1, courseRecords2, courseRecords3;
    ArrayList<String> courseRecordsArray1 = new ArrayList<>();
    ArrayList<String> courseRecordsArray2 = new ArrayList<>();
    ArrayList<String> courseRecordsArray3 = new ArrayList<>();
    ArrayAdapter<String> adapter1, adapter2, adapter3;
    Button returnInstructor;
    //String selectedCourse = null;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.instructorrecord);

        Intent intent = getIntent();
        final String email = intent.getStringExtra("email");
        final String password = intent.getStringExtra("password");
        final String name = intent.getStringExtra("name");
        final String inid = intent.getStringExtra("inid");
        courseRecords1 = (ListView) findViewById(R.id.courseRecords1);
        courseRecords2 = (ListView) findViewById(R.id.courseRecords2);
        courseRecords3 = (ListView) findViewById(R.id.courseRecords3);
        returnInstructor = (Button) findViewById(R.id.instructor_main_btn);

        returnInstructor.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent returnInstructorIntent = new Intent(InstructorRecord.this, InstructorMain.class);
                returnInstructorIntent.putExtra("email", email);
                returnInstructorIntent.putExtra("password", password);
                returnInstructorIntent.putExtra("name", name);
                returnInstructorIntent.putExtra("inid",inid);
                InstructorRecord.this.startActivity(returnInstructorIntent);
            }
        });

        adapter1 = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, courseRecordsArray1);
        courseRecords1.setAdapter(adapter1);
        displayCourses1(inid);

        adapter2 = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, courseRecordsArray2);
        courseRecords2.setAdapter(adapter2);
        displayCourses2(inid);

        adapter3 = new ArrayAdapter<>(this, android.R.layout.simple_list_item_1, courseRecordsArray3);
        courseRecords3.setAdapter(adapter3);
        displayCourses3(inid);
    }

    // 3 different displayCourses functions required because there are
    // 3 different output string formats

    // Displays all sections being taught or previously taught
    private void displayCourses1(String inid) {
        Response.Listener<String> responseListener = new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    Log.d("SubmitQueryHelp", response);
                    JSONArray jsonArray = new JSONArray(response);
                    courseRecordsArray1.clear();
                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject course = jsonArray.getJSONObject(i);
                        String record = course.getString("course_id") + ", " + course.getString("section_id")
                                + ", " + course.getString("semester") + ", " + course.getString("year");
                        courseRecordsArray1.add(record);
                    }
                    adapter1.notifyDataSetChanged();

                } catch (JSONException e) {
                    Log.d("InstructorRecord", "displayCourses1 failed");
                    e.printStackTrace();
                    AlertDialog.Builder builder = new AlertDialog.Builder(InstructorRecord.this);
                    builder.setMessage("Retrieving records of taught sections failed").setNegativeButton("Retry", null).create().show();
                }
            }
        };

        InstructorRecordRequest queryRequest = new InstructorRecordRequest(inid,getString(R.string.url) + "instructor_record_1.php", responseListener);
        RequestQueue queue = Volley.newRequestQueue(InstructorRecord.this);
        queue.add(queryRequest);
    }

    // Sec. taught by instructor before Spring 2025 with the names + grades of students who took them
    private void displayCourses2(String inid) {
        Response.Listener<String> responseListener = new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    Log.d("SubmitQueryHelp", response);
                    JSONArray jsonArray = new JSONArray(response);
                    courseRecordsArray2.clear();
                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject course = jsonArray.getJSONObject(i);
                        String record = course.getString("course_id") + ", " + course.getString("section_id")
                                + ", " + course.getString("semester") + ", " + course.getString("year") + ", "
                                + course.getString("name") + ", " + course.getString("grade");
                        courseRecordsArray2.add(record);
                    }
                    adapter2.notifyDataSetChanged();

                } catch (JSONException e) {
                    Log.d("InstructorRecord", "displayCourses2 failed");
                    e.printStackTrace();
                    AlertDialog.Builder builder = new AlertDialog.Builder(InstructorRecord.this);
                    builder.setMessage("Retrieving records of taught courses prior to current semester failed").setNegativeButton("Retry", null).create().show();
                }
            }
        };

        InstructorRecordRequest queryRequest = new InstructorRecordRequest(inid,getString(R.string.url) + "instructor_record_2.php", responseListener);
        RequestQueue queue = Volley.newRequestQueue(InstructorRecord.this);
        queue.add(queryRequest);
    }

    // Sec. currently being taught by instructor with only the names of current enrolled students
    private void displayCourses3(String inid) {
        Response.Listener<String> responseListener = new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    Log.d("SubmitQueryHelp", response);
                    JSONArray jsonArray = new JSONArray(response);
                    courseRecordsArray3.clear();
                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject course = jsonArray.getJSONObject(i);
                        String record = course.getString("course_id") + ", " + course.getString("section_id")
                                + ", " + course.getString("semester") + ", " + course.getString("year") + ", "
                                + course.getString("name");
                        courseRecordsArray3.add(record);
                    }
                    adapter3.notifyDataSetChanged();

                } catch (JSONException e) {
                    Log.d("InstructorRecord", "displayCourses3 failed");
                    e.printStackTrace();
                    AlertDialog.Builder builder = new AlertDialog.Builder(InstructorRecord.this);
                    builder.setMessage("Retrieving records of currently taught sections failed").setNegativeButton("Retry", null).create().show();
                }
            }
        };

        InstructorRecordRequest queryRequest = new InstructorRecordRequest(inid,getString(R.string.url) + "instructor_record_3.php", responseListener);
        RequestQueue queue = Volley.newRequestQueue(InstructorRecord.this);
        queue.add(queryRequest);
    }
}
