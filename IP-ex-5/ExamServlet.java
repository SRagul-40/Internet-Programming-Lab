
import java.io.IOException;
import java.io.PrintWriter;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
@WebServlet("/ExamServlet")
public class ExamServlet extends HttpServlet {
    private static final long serialVersionUID = 1L;
    protected void doPost(HttpServletRequest request,
                          HttpServletResponse response)
            throws ServletException, IOException {
        request.setCharacterEncoding("UTF-8");
        response.setContentType("text/html;charset=UTF-8");
        String name = request.getParameter("name");
        String rollno = request.getParameter("rollno");
        String department = request.getParameter("department");
        String q1 = request.getParameter("q1");
        String q2 = request.getParameter("q2");
        String q3 = request.getParameter("q3");
        String q4 = request.getParameter("q4");
        String q5 = request.getParameter("q5");
        String q6 = request.getParameter("q6");
        String q7 = request.getParameter("q7");
        String q8 = request.getParameter("q8");
        String q9 = request.getParameter("q9");
        String q10 = request.getParameter("q10");
        String[] correctAnswers = {
            "c",  "b",  "c",  "c", "b", "a",  "b",  "b"  };
        String[] studentAnswers = {
            q1, q2, q3, q4, q5, q6, q7, q8
        };
        int score = 0;
        int unanswered = 0;
        for (int i = 0; i < correctAnswers.length; i++) {
            if (studentAnswers[i] == null ||
                studentAnswers[i].trim().isEmpty()) {
                unanswered++;
            } else if (studentAnswers[i].equals(correctAnswers[i])) {
                score++;
            }
      }
        if (q9 == null || q9.trim().isEmpty()) {
            unanswered++;
        } else if (q9.trim().equalsIgnoreCase("onclick")) {
            score++;
        }
        if (q10 == null || q10.trim().isEmpty()) {
            unanswered++;
        } else if (q10.trim().equalsIgnoreCase(
                "uniform resource locator")) {
            score++;
        }
        int percentage = score * 10;
        String status;
        if (score >= 5) {
            status = "PASS";
        } else {
            status = "FAIL";
        }
        PrintWriter out = response.getWriter();
        out.println("<!DOCTYPE html>");
        out.println("<html>");
        out.println("<head>");
        out.println("<meta charset='UTF-8'>");
        out.println("<title>Exam Result</title>");
        out.println("<style>");
        out.println("* { box-sizing: border-box; }");
        out.println("body {");
        out.println("font-family: Arial, sans-serif;");
        out.println("background: #f2f5f9;");
        out.println("margin: 0;");
        out.println("padding: 20px;");
        out.println("}");
        out.println(".container {");
        out.println("max-width: 700px;");
        out.println("margin: 50px auto;");
        out.println("background: white;");
        out.println("padding: 30px;");
        out.println("border-radius: 10px;");
        out.println("box-shadow: 0 4px 15px rgba(0,0,0,0.08);");
        out.println("}");
        out.println("h1 {");
        out.println("text-align: center;");
        out.println("color: #1e3a8a;");
        out.println("}");
        out.println(".student-info {");
        out.println("background: #f8fafc;");
        out.println("padding: 20px;");
        out.println("border-left: 4px solid #2563eb;");
        out.println("border-radius: 8px;");
        out.println("}");
        out.println(".student-info p {");
        out.println("font-size: 16px;");
        out.println("}");
        out.println(".pass {");
        out.println("background: #dcfce7;");
        out.println("color: #166534;");
        out.println("padding: 20px;");
        out.println("text-align: center;");
        out.println("border-radius: 8px;");
        out.println("margin-top: 20px;");
        out.println("}");
        out.println(".fail {");
        out.println("background: #fee2e2;");
        out.println("color: #991b1b;");
        out.println("padding: 20px;");
        out.println("text-align: center;");
        out.println("border-radius: 8px;");
        out.println("margin-top: 20px;");
        out.println("}");
        out.println(".score {");
        out.println("font-size: 28px;");
        out.println("font-weight: bold;");
        out.println("}");
        out.println(".back-btn {");
        out.println("display: block;");
        out.println("width: 200px;");
        out.println("margin: 25px auto 0;");
        out.println("padding: 12px;");
        out.println("background: #2563eb;");
        out.println("color: white;");
        out.println("text-align: center;");
        out.println("text-decoration: none;");
        out.println("border-radius: 5px;");
        out.println("}");
        out.println("</style>");
        out.println("</head>");
        out.println("<body>");
        out.println("<div class='container'>");
        out.println("<h1>Online Examination System</h1>");
        out.println("<p style='text-align:center'>");
        out.println("<b>Internet Programming (IP)</b>");
        out.println("</p>");
        out.println("<div class='student-info'>");
        out.println("<h2>Student Details</h2>");
        out.println("<p><b>Name:</b> "+ escapeHtml(name) + "</p>");
        out.println("<p><b>Roll Number:</b> "+ escapeHtml(rollno) + "</p>");
        out.println("<p><b>Department:</b> "+ escapeHtml(department) + "</p>");
        out.println("</div>");
        if (status.equals("PASS")) {
            out.println("<div class='pass'>");
        } else {
            out.println("<div class='fail'>");}
        out.println("<h2>Exam Result</h2>");
        out.println("<p class='score'>");
        out.println("Score: " + score + " / 10");
        out.println("</p>");
        out.println("<p>");
        out.println("Percentage: " + percentage + "%");
        out.println("</p>");
        out.println("<p>");
        out.println("Unanswered Questions: " + unanswered);
        out.println("</p>");
        out.println("<h2>");
        out.println("Result: " + status);
        out.println("</h2>");
        out.println("</div>");
        out.println("<a class='back-btn' href='index.html'>");
        out.println("Back to Exam");
        out.println("</a>");
        out.println("</div>");
        out.println("</body>");
        out.println("</html>");
    } private String escapeHtml(String value) {
        if (value == null) {
            return "";}
        return value
                .replace("&", "&amp;")
                .replace("<", "&lt;")
                .replace(">", "&gt;")
                .replace("\"", "&quot;")
                .replace("'", "&#39;");
    }}
