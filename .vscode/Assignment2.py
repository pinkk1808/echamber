import sys
import time
import random
from OpenGL.GL import *
from OpenGL.GLUT import *
from OpenGL.GLU import *
import numpy as np

# --- Part 1: Midpoint Line Drawing Algorithm ---

def find_zone(x1, y1, x2, y2):
    """Determines the zone of a line segment."""
    dx = x2 - x1
    dy = y2 - y1
    if abs(dx) > abs(dy):  # Zones 0, 3, 4, 7
        if dx > 0 and dy >= 0:
            return 0
        elif dx < 0 and dy >= 0:
            return 3
        elif dx < 0 and dy < 0:
            return 4
        else: # dx > 0 and dy < 0
            return 7
    else:  # Zones 1, 2, 5, 6
        if dx >= 0 and dy > 0:
            return 1
        elif dx < 0 and dy > 0:
            return 2
        elif dx < 0 and dy < 0:
            return 5
        else: # dx >= 0 and dy < 0
            return 6

def convert_to_zone_0(x, y, zone):
    """Converts a point from its original zone to Zone 0."""
    if zone == 0:
        return x, y
    elif zone == 1:
        return y, x
    elif zone == 2:
        return y, -x
    elif zone == 3:
        return -x, y
    elif zone == 4:
        return -x, -y
    elif zone == 5:
        return -y, -x
    elif zone == 6:
        return -y, x
    elif zone == 7:
        return x, -y
    return x,y # Should not happen

def convert_from_zone_0(x, y, zone):
    """Converts a point from Zone 0 back to its original zone."""
    if zone == 0:
        return x, y
    elif zone == 1:
        return y, x
    elif zone == 2:
        return -y, x
    elif zone == 3:
        return -x, y
    elif zone == 4:
        return -x, -y
    elif zone == 5:
        return -y, -x
    elif zone == 6:
        return y, -x
    elif zone == 7:
        return x, -y
    return x,y # Should not happen

def draw_point(x, y):
    """Draws a single point on the screen."""
    glBegin(GL_POINTS)
    glVertex2f(x, y)
    glEnd()

def midpoint_line(x1, y1, x2, y2):
    """Draws a line using the Midpoint Line Algorithm for any zone."""
    zone = find_zone(x1, y1, x2, y2)

    # Convert endpoints to Zone 0
    x1_zone0, y1_zone0 = convert_to_zone_0(x1, y1, zone)
    x2_zone0, y2_zone0 = convert_to_zone_0(x2, y2, zone)
    
    # The Zone 0 algorithm assumes drawing from left to right.
    # If the converted start point is to the right of the end point, swap them.
    if x1_zone0 > x2_zone0:
        x1_zone0, x2_zone0 = x2_zone0, x1_zone0
        y1_zone0, y2_zone0 = y2_zone0, y1_zone0

    dx = x2_zone0 - x1_zone0
    dy = y2_zone0 - y1_zone0
    
    d = 2 * dy - dx
    inc_e = 2 * dy
    inc_ne = 2 * (dy - dx)

    x, y = x1_zone0, y1_zone0
    
    while x <= x2_zone0:
        # Convert back to original zone before drawing
        original_x, original_y = convert_from_zone_0(x, y, zone)
        draw_point(original_x, original_y)
        
        if d > 0:
            d += inc_ne
            y += 1
        else:
            d += inc_e
        x += 1

# --- Part 2: "Catch the Diamonds!" Game ---

class GameState:
    """Manages the state of the game."""
    def __init__(self):
        self.window_width = 800
        self.window_height = 600
        self.score = 0
        self.game_over = False
        self.paused = False
        
        # Timing for consistent animation speed
        self.last_frame_time = time.time()
        self.delta_time = 0

        # Catcher properties
        self.catcher_width = 120
        self.catcher_height = 20
        self.catcher_x = (self.window_width - self.catcher_width) / 2
        self.catcher_y = 10
        self.catcher_speed = 400.0 # pixels per second
        self.catcher_color = (1.0, 1.0, 1.0) # White

        # Diamond properties
        self.diamond_size = 20
        self.diamond_x = 0
        self.diamond_y = self.window_height
        self.diamond_speed = 100.0 # pixels per second
        self.diamond_speed_increase = 10.0 # speed increase per score
        self.diamond_color = (1.0, 1.0, 0.0)
        self.diamond_active = False

        # UI Button properties
        self.button_size = 20
        # Restart button (left arrow)
        self.restart_button_pos = (40, self.window_height - 40)
        # Pause/Play button
        self.pause_button_pos = (self.window_width / 2, self.window_height - 40)
        # Exit button (cross)
        self.exit_button_pos = (self.window_width - 40, self.window_height - 40)

    def reset(self):
        """Resets the game to its initial state."""
        print("Starting Over")
        self.score = 0
        self.game_over = False
        self.paused = False
        self.catcher_x = (self.window_width - self.catcher_width) / 2
        self.catcher_color = (1.0, 1.0, 1.0)
        self.diamond_speed = 100.0
        self.spawn_new_diamond()
        glutPostRedisplay()
        
    def spawn_new_diamond(self):
        """Creates a new diamond at a random horizontal position."""
        self.diamond_x = random.randint(self.diamond_size, self.window_width - self.diamond_size)
        self.diamond_y = self.window_height
        self.diamond_color = (random.random(), random.random(), random.random())
        self.diamond_active = True
        # Increase speed with score
        self.diamond_speed = 100.0 + self.score * self.diamond_speed_increase


# Create a global instance of the game state
game = GameState()

# --- Drawing Functions for Game Objects ---

def draw_catcher(x, y, width, height, color):
    """Draws the catcher using midpoint_line."""
    glColor3f(*color)
    midpoint_line(x, y, x + width, y)
    midpoint_line(x, y, x + 10, y + height)
    midpoint_line(x + width, y, x + width - 10, y + height)
    midpoint_line(x + 10, y + height, x + width - 10, y + height)

def draw_diamond(x, y, size, color):
    """Draws a diamond using midpoint_line."""
    glColor3f(*color)
    half_size = size / 2
    midpoint_line(x, y + half_size, x + half_size, y) # Top-right
    midpoint_line(x + half_size, y, x, y - half_size) # Bottom-right
    midpoint_line(x, y - half_size, x - half_size, y) # Bottom-left
    midpoint_line(x - half_size, y, x, y + half_size) # Top-left

def draw_ui():
    """Draws all UI elements."""
    # Restart Button (Teal Left Arrow)
    glColor3f(0.0, 0.8, 0.8)
    x, y = game.restart_button_pos
    s = game.button_size / 2
    midpoint_line(x + s, y + s, x - s, y)
    midpoint_line(x - s, y, x + s, y - s)

    # Pause/Play Button (Amber)
    glColor3f(1.0, 0.75, 0.0)
    x, y = game.pause_button_pos
    s = game.button_size / 2
    if game.paused: # Draw Play Icon (Triangle)
        midpoint_line(x - s, y + s, x + s, y)
        midpoint_line(x + s, y, x - s, y - s)
        midpoint_line(x - s, y - s, x - s, y + s)
    else: # Draw Pause Icon (Two vertical lines)
        midpoint_line(x - s/2, y + s, x - s/2, y - s)
        midpoint_line(x + s/2, y + s, x + s/2, y - s)

    # Exit Button (Red Cross)
    glColor3f(1.0, 0.0, 0.0)
    x, y = game.exit_button_pos
    s = game.button_size / 2
    midpoint_line(x - s, y - s, x + s, y + s)
    midpoint_line(x - s, y + s, x + s, y - s)

# --- Game Logic and Collision Detection ---

def aabb_collision(box1_x, box1_y, box1_w, box1_h, box2_x, box2_y, box2_w, box2_h):
    """Axis-Aligned Bounding Box collision detection."""
    return (box1_x < box2_x + box2_w and
            box1_x + box1_w > box2_x and
            box1_y < box2_y + box2_h and
            box1_y + box1_h > box2_y)

def update_game_state():
    """Updates the game logic for each frame."""
    global game
    
    # Calculate delta time for frame-rate independence
    current_time = time.time()
    game.delta_time = current_time - game.last_frame_time
    game.last_frame_time = current_time

    if game.game_over or game.paused:
        return
        
    # Update diamond position
    if game.diamond_active:
        game.diamond_y -= game.diamond_speed * game.delta_time

    # Check for collision
    diamond_aabb_x = game.diamond_x - game.diamond_size / 2
    diamond_aabb_y = game.diamond_y - game.diamond_size / 2
    
    if aabb_collision(game.catcher_x, game.catcher_y, game.catcher_width, game.catcher_height,
                        diamond_aabb_x, diamond_aabb_y, game.diamond_size, game.diamond_size):
        game.score += 1
        print(f"Score: {game.score}")
        game.spawn_new_diamond()
    
    # Check if diamond is missed
    if game.diamond_y < 0:
        print(f"Game Over! Score: {game.score}")
        game.game_over = True
        game.diamond_active = False
        game.catcher_color = (1.0, 0.0, 0.0) # Turn catcher red

# --- GLUT Callbacks ---

def display():
    """The main display callback."""
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT)
    glLoadIdentity()
    
    # Set the viewport and projection
    glViewport(0, 0, game.window_width, game.window_height)
    glMatrixMode(GL_PROJECTION)
    glLoadIdentity()
    gluOrtho2D(0, game.window_width, 0, game.window_height)
    glMatrixMode(GL_MODELVIEW)
    glLoadIdentity()

    # Update game logic
    update_game_state()

    # Draw all game elements
    draw_catcher(game.catcher_x, game.catcher_y, game.catcher_width, game.catcher_height, game.catcher_color)
    if game.diamond_active:
        draw_diamond(game.diamond_x, game.diamond_y, game.diamond_size, game.diamond_color)
    draw_ui()
    
    glutSwapBuffers()

def special_keys(key, x, y):
    """Handles special key presses (arrow keys)."""
    if not game.game_over and not game.paused:
        if key == GLUT_KEY_LEFT:
            game.catcher_x -= game.catcher_speed * game.delta_time
        elif key == GLUT_KEY_RIGHT:
            game.catcher_x += game.catcher_speed * game.delta_time
        
        # Clamp catcher position to stay within the screen
        game.catcher_x = max(0, min(game.catcher_x, game.window_width - game.catcher_width))
        
def mouse_click(button, state, x, y):
    """Handles mouse clicks for UI buttons."""
    if button == GLUT_LEFT_BUTTON and state == GLUT_DOWN:
        # GLUT's y-coordinate is from the top, so we invert it
        y = game.window_height - y
        
        # Check Restart button
        bx, by = game.restart_button_pos
        bs = game.button_size
        if (bx - bs < x < bx + bs) and (by - bs < y < by + bs):
            game.reset()
        
        # Check Pause/Play button
        bx, by = game.pause_button_pos
        bs = game.button_size
        if (bx - bs < x < bx + bs) and (by - bs < y < by + bs):
            if not game.game_over:
                game.paused = not game.paused
        
        # Check Exit button
        bx, by = game.exit_button_pos
        bs = game.button_size
        if (bx - bs < x < bx + bs) and (by - bs < y < by + bs):
            print(f"Goodbye! Final Score: {game.score}")
            glutLeaveMainLoop()
            
def animate():
    """Idle callback for animation."""
    glutPostRedisplay()

def main():
    """Main function to initialize GLUT and start the game."""
    glutInit(sys.argv)
    glutInitDisplayMode(GLUT_RGBA | GLUT_DOUBLE | GLUT_DEPTH)
    glutInitWindowSize(game.window_width, game.window_height)
    glutInitWindowPosition(100, 100)
    glutCreateWindow(b"Catch the Diamonds!")

    glutDisplayFunc(display)
    glutIdleFunc(animate)
    glutSpecialFunc(special_keys)
    glutMouseFunc(mouse_click)

    glClearColor(0.0, 0.0, 0.0, 1.0) # Black background
    glPointSize(2.0)
    
    game.spawn_new_diamond() # Start with the first diamond
    print(f"Game Started! Use Left/Right arrow keys to move.")
    
    glutMainLoop()

if __name__ == "__main__":
    main()