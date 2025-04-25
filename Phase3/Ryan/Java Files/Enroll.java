package com.database.db2;

import android.app.AlertDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Button;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class Enroll extends AppCompatActivity {
    ListView courseList;
    Button enrollBtn;
    ArrayList<String> courseListArray = new ArrayList<>();
    ArrayAdapter<String> adapter;
    Button returnStudent;
    String selectedCourse = null;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.enroll);
        Intent intent = getIntent();
        final String email = intent.getStringExtra("email");
        final String password = intent.getStringExtra("password");
        final String name = intent.getStringExtra("name");
        final String sid = intent.getStringExtra("sid");
        courseList = (ListView) findViewById(R.id.courseList);
        enrollBtn = (Button) findViewById(R.id.enroll_btn);
        returnStudent = (Button) findViewById(R.id.student_main_btn);
        returnStudent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent returnStudentIntent = new Intent(Enroll.this, StudentMain.class);
                returnStudentIntent.putExtra("email", email);
                returnStudentIntent.putExtra("password", password);
                returnStudentIntent.putExtra("name", name);
                returnStudentIntent.putExtra("sid",sid);
                Enroll.this.startActivity(returnStudentIntent);
            }
        });

        adapter = new ArrayAdapter<>(this, android.R.layout.simple_list_item_single_choice, courseListArray);
        courseList.setAdapter(adapter);
        courseList.setChoiceMode(ListView.CHOICE_MODE_SINGLE);
        displayCourses(sid);
        courseList.setOnItemClickListener((parent, view, position, id) -> {
            selectedCourse = courseListArray.get(position);
        });
        enrollBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (selectedCourse != null) {
                    enrollCourse(email, password, name, sid, selectedCourse);
                } else {
                    AlertDialog.Builder builder = new AlertDialog.Builder(Enroll.this);
                    builder.setMessage("Select A Course First").setNegativeButton("Retry", null).create().show();
                }
            }
        });
    }
    private void displayCourses(String sid) {
        Response.Listener<String> responseListener = new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    Log.d("SubmitQueryHelp", response);
                    JSONArray jsonArray = new JSONArray(response);
                    courseListArray.clear();
                    for (int i = 0; i < jsonArray.length(); i++) {
                        JSONObject course = jsonArray.getJSONObject(i);
                        String option = course.getString("course_id") + ", " + course.getString("section_id");
                        courseListArray.add(option);
                    }
                    adapter.notifyDataSetChanged();

                } catch (JSONException e) {
                    Log.d("error fails", "it failed");
                    e.printStackTrace();
                    AlertDialog.Builder builder = new AlertDialog.Builder(Enroll.this);
                    builder.setMessage("Retrieving Available Courses Failed").setNegativeButton("Retry", null).create().show();
                }
            }
        };

        ListCourseRequest queryRequest = new ListCourseRequest(sid,getString(R.string.url) + "list_available_courses.php", responseListener);
        RequestQueue queue = Volley.newRequestQueue(Enroll.this);
        queue.add(queryRequest);
    }

    private void enrollCourse(String email, String password, String name, String sid, String course) {
        String[] halves = course.split(", ");
        if (halves.length != 2)
            return;
        String course_id = halves[0];
        String section_id = halves[1];
        Response.Listener<String> responseListener = new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    Log.d("SubmitQueryHelp", response);
                    JSONObject jsonResponse = new JSONObject(response);
                    boolean success = jsonResponse.getBoolean("success");
                    if(success){
                        Intent enrollValidIntent = new Intent(Enroll.this, EnrollValid.class);
                        enrollValidIntent.putExtra("email", email);
                        enrollValidIntent.putExtra("password", password);
                        enrollValidIntent.putExtra("name", name);
                        enrollValidIntent.putExtra("sid",sid);
                        Enroll.this.startActivity(enrollValidIntent);
                    } else{
                        AlertDialog.Builder builder = new AlertDialog.Builder(Enroll.this);
                        builder.setMessage("Enroll In Course Failed").setNegativeButton("Retry", null).create().show();
                    }

                } catch (JSONException e) {
                    Log.d("error fails", "it failed");
                    e.printStackTrace();
                }
            }
        };

        EnrollQueryRequest queryRequest = new EnrollQueryRequest(sid, course_id, section_id,getString(R.string.url) + "enroll_course.php", responseListener);
        RequestQueue queue = Volley.newRequestQueue(Enroll.this);
        queue.add(queryRequest);
    }
}